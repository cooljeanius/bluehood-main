<?php
	//FIXME: this file is included in others; might need to check for name clashes:
	$s = [
		'login' => ['ja' => "ログイン", 'en' => "Login", ],
		'needslogin' => [
			'ja' => "これより先はTwitterアカウントによるログインが必要です。",
			'en' => "Logging in with a Twitter account is required beyond this point.",
		],
		'ratelim' => [
			'ja' => "レートリミットを超過しました。<br>しばらくしてから、再度アクセスしてください。",
			'en' => "Rate limit exceeded. <br> Please try again after a while.",
		],
		'mypage' => ['ja' => "マイページ", 'en' => "My Page", ],
		'community' => ['ja' => "コミュニティ", 'en' => "Community", ],
		'notif' => ['ja' => "通知", 'en' => "Notification", ],
		'notweets' => ['ja' => "ツイートがありません。", 'en' => "No tweets in collection.", ],
		'more' => ['ja' => "もっとみる", 'en' => "More", ],
		'defaulterrormsg' => [
			'ja' => "エラーが発生しました。", 'en' => "An error occurred.",
		],
		'senderr' => [
			'ja' => "このエラーを @bluehood_admin に報告できます。",
			'en' => "You can report this error to @bluehood_admin.",
		],
		//'' => ['ja' => "", 'en' => "", ],
	];
	session_start();
	require_once 'twitteroauth/autoload.php';
	use Abraham\TwitterOAuth\TwitterOAuth;

	define('OAUTH_CALLBACK', DOMAIN.ROOT_URL.'callback.php');

	define('ALL_POSTS', '932243624233979905');
	define('COLLECTON_STAMP', '945172613063520256');
	define('COMMID_STAMP', 'stamp');

	define('THEME_COLOR', '#55acee');

	if (isset($_SESSION['theme'])) $theme = $_SESSION['theme'];
	else $theme = 'auto';
	switch ($theme){
		case 'auto':
		if ((getdate()['hours']>=6) && (getdate()['hours']<18)){
			define('SITE_NAME', 'BlueHood');
			define('THEME', 'light');
		}else{
			define('SITE_NAME', 'BanWolf (BlueHood)');
			define('THEME', 'dark');
		}
		break;

		case 'light':
		define('SITE_NAME', 'BlueHood');
		define('THEME', 'light');
		break;

		case 'dark':
		define('SITE_NAME', 'BanWolf (BlueHood)');
		define('THEME', 'dark');
		break;
	}

	switch(useragent()){
		case '3ds':
		define('MAX_TWEETS', 10);
		break;

		case 'new3ds':
		define('MAX_TWEETS', 10);
		break;

		default:
		define('MAX_TWEETS', 15);
		break;
	}

	if (0)
	if (isset($_SESSION['access_token'])){	/* ログイン判定 (login determination) */
		if ((!isset($_SESSION['notification_time']))||(time() - $_SESSION['notification_time'] >= 600/* 10分 (10 minutes) */) ){
			$twitter = twitter_start();
		        mysql_start();
			$event_cnt = 0;
		        $res = mysql_query("select id, favorite_count, retweet_count from tweet where (screen_name = '".$_SESSION['twitter']['account']['user']->screen_name."') AND (time between now() - INTERVAL 1 WEEK and now())");
			$_SESSION['notification_update'] = [];
		        while($row = mysql_fetch_assoc($res)){
				$status = $twitter->get('statuses/show', ['id' => $row['id']]);
				if (isset($status->favorite_count)){	/* ツイートが存在 (Tweet is present) */
					if ($row['favorite_count'] < $status->favorite_count) $event_cnt += $status->favorite_count - $row['favorite_count'];
					if ($row['retweet_count'] < $status->retweet_count) $event_cnt += $status->retweet_count - $row['retweet_count'];
					//mysql_query("update tweet set favorite_count = ".$status->favorite_count.", retweet_count = ".$status->retweet_count." where id = '".$row['id']."'");
					array_push($_SESSION['notification_update'], "update tweet set favorite_count = ".$status->favorite_count.", retweet_count = ".$status->retweet_count." where id = '".$row['id']."'");
				}
		        }
		        mysql_close();
			$_SESSION['notification'] = $event_cnt;
			$_SESSION['notification_time'] = time();
		}
	}

	function useragent(){
		$ua = $_SERVER['HTTP_USER_AGENT'];
		if (strpos($ua, 'Nintendo WiiU') !== false){
			return 'wiiu';
		}else if (strpos($ua, 'New Nintendo 3DS') !== false){	// 先にNew 3DS
			return 'new3ds';
		}else if (strpos($ua, 'Mozilla/5.0 (iPhone; CPU iPhone OS 6_0 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10A403 Safari/8536.25') !== false){
			return 'new3ds';
                }else if (strpos($ua, 'Nintendo 3DS') !== false){
			return '3ds';
		}else{
			return 'others';
		}
	}

	function check_limit($elem){
		if (is_object($elem)){
			foreach($elem as $child) if(check_limit($child)){
				return true;
			}
		}else{
			if ($elem === 0) return true;
		}
		return false;
	}

	function twitter_start(){
		try{
		if (!isset($_SESSION['access_token'])){
			$conn = new TwitterOAuth(CONSUMER_KEY,CONSUMER_SECRET);

			$req_token = $conn->oauth('oauth/request_token', array('oauth_callback' => OAUTH_CALLBACK));
			$_SESSION['oauth_token'] = $req_token['oauth_token'];
			$_SESSION['oauth_token_secret'] = $req_token['oauth_token_secret'];

			$verify_url = $conn->url('oauth/authenticate', array('oauth_token' => $req_token['oauth_token']));
			//$html = '<html><head><script type="text/javascript">function redirect(){top.location.href="'.$verify_url.'";}</script></head><body onload="redirect();"></body></html>';
			//die($html);
			$_SESSION['callback_referer'] = DOMAIN.$_SERVER["REQUEST_URI"];
			?>
<!DOCTYPE html>
<html lang = "ja">
	<?php head(); ?>
	<body>
		<h2 class="topbar">ログイン</h2>
		<div class="main">
			<br>
			<div class="card card-article marginleft marginright">
				<p>これより先はTwitterアカウントによるログインが必要です。</p>
				<a href="<?php echo $verify_url; ?>"><img src="<?php echo ROOT_URL; ?>img/sign-in-with-twitter-gray.png" alt="Sign in with Twitter" style="cursor: pointer; "></a>
			</div>
		</div>
	</body>
</html>
			<?php
			die();
		}

		if (isset($_SESSION['twitter']['start'])){
			$twitter = unserialize($_SESSION['twitter']['start']);
		}else{
			$twitter = new TwitterOAuth(CONSUMER_KEY,CONSUMER_SECRET, $_SESSION['access_token'], $_SESSION['access_secret']);
			$_SESSION['twitter']['start'] = serialize($twitter);
		}

		if (!isset($_SESSION['twitter']['account'])){
			mysql_start();
			$account = [];

			$account['settings'] = $twitter->get('account/settings');
			$account['user'] = $twitter->get('users/show', ['screen_name' => $account['settings']->screen_name]);

			$_SESSION['twitter']['account'] = $account;
			$_SESSION['twitter']['screen_name'] = $_SESSION['twitter']['account']['user']->screen_name;
			$_SESSION['twitter']['id'] = $_SESSION['twitter']['account']['user']->id;

			$res = mysql_fetch_assoc(mysql_query("select id, collection_id, album_id from user where id=".$account['user']->id));
			mysql_throw();
                        if (!$res['id']){
				mysql_query("insert into user (id) values (".$account['user']->id.")");
				mysql_throw();
				header('location: '.DOMAIN.ROOT_URL.'guide.php');
	                        mysql_close();
				exit;
			}

			/*
			コレクションID (Collection ID)
			null…未走査 (null…unscanned)
			0…存在しない (0…does not exist)
			*/
                        unset($account['collection_id']);
                        if (is_null($res['collection_id'])){
				mysql_query("update user set collection_id = 0 where id=".$account['user']->id);
				mysql_throw();
				$list = $twitter->get('collections/list', ['screen_name' => $account['user']->screen_name, 'count' => 200])->objects->timelines;
                        	foreach($list as $id => $collection){
                       	        	if ($collection->name == 'Twiverse'){
                                        	$account['collection_id'] = $id;
						mysql_query("update user set collection_id = ".str_replace('custom-', '', $id)." where id=".$account['user']->id);
						mysql_throw();
						break;
                        	        }
                        	}
			}else{
				if ($res['collection_id']) $account['collection_id'] = 'custom-'.$res['collection_id'];
			}

                        unset($account['album_id']);
                        if (is_null($res['album_id'])){
				mysql_query("update user set album_id = 0 where id=".$account['user']->id);
				mysql_throw();
				$list = $twitter->get('collections/list', ['screen_name' => $account['user']->screen_name, 'count' => 200])->objects->timelines;
                        	foreach($list as $id => $collection){
                       	        	if ($collection->name == 'Twiverse_album'){
                                        	$account['album_id'] = $id;
						mysql_query("update user set album_id = ".str_replace('custom-', '', $id)." where id=".$account['user']->id);
						mysql_throw();
						break;
                        	        }
                        	}
			}else{
				if ($res['album_id']) $account['album_id'] = 'custom-'.$res['album_id'];
			}

			$res = mysql_fetch_assoc(mysql_query("select theme from user where id=".$account['user']->id));
			mysql_throw();
			$_SESSION['theme'] = $res['theme'];

			$_SESSION['twitter']['account'] = $account;
			$_SESSION['twitter']['screen_name'] = $_SESSION['twitter']['account']['user']->screen_name;
                        mysql_close();
		}

		$limit = $twitter->get('application/rate_limit_status', []);
		if (check_limit($limit)){
			echo 'レートリミットを超過しました。<br>しばらくしてから、再度アクセスしてください。';
			//English: Rate limit exceeded. <br> Please try again after a while.
			var_dump($limit);
			exit;
		}
		return $twitter;
		}catch(Exception $e){
			catch_default($e);
		}
	}

	function twitter_reader(){
		if (isset($_SESSION['access_token'])){
			$twitter = twitter_start();
			$twitter->use_admin = false;
		}else{
			if (isset($_SESSION['twitter']['reader'])){
				$twitter = unserialize($_SESSION['twitter']['reader']);
			}else{
				$twitter = new TwitterOAuth(READER_KEY, READER_SECRET, READER_COMSUMER_KEY, READER_COMSUMER_SECRET);
				$_SESSION['twitter']['reader'] = serialize($twitter);
			}

			$twitter->use_admin = true;
			if (check_limit($twitter->get('application/rate_limit_status', []))) die('レートリミットを超過しました。<br>しばらくしてから、再度アクセスしてください。');
			//English: Rate limit exceeded. <br> Please try again after a while.
		}

		return $twitter;
	}

	function twitter_admin(){
		$twitter = new TwitterOAuth(ADMIN_KEY, ADMIN_SECRET, ADMIN_COMSUMER_KEY, ADMIN_COMSUMER_SECRET);
		if (check_limit($twitter->get('application/rate_limit_status', []))) die('レートリミットを超過しました。<br>しばらくしてから、再度アクセスしてください。');
		//English: Rate limit exceeded. <br> Please try again after a while.
		return $twitter;
	}

        function twitter_trimhash($hash){
                $trimed = '';
                while($hash){
                        $ch = mb_substr($hash, 0, 1);
                        $hash = mb_substr($hash, 1);
                        if (preg_match('/^[0-9a-zA-Zぁ-んァ-ヶー一-龠]$/u', $ch)) $trimed .= $ch;
                }

                return $trimed;
        }

	function twitter_throw($res){
		if (isset($res->error)||isset($res->errors)) throw new Exception(print_r($res->errors, true));
		return $res;
	}

	function mysql_start(){
		$db = mysql_connect(MYSQL_ADDR, MYSQL_USER, MYSQL_PASS);
		if (!$db) return mysql_error();
		mysql_select_db('twiverse');
		mysql_query('SET NAMES utf8', $db);
		return $db;
	}

	function mysql_throw($thru = null){	// MySQL のエラーがあったとき throw する (Throw when there is an error from MySQL)
		$err = mysql_error();
		if (!empty($err)) throw new Exception($err);
		return $thru;
	}

				function twitter_text($status){
					$text = nl2br(htmlspecialchars($status->text));

					foreach($status->entities->hashtags as $tag){
						$text = str_replace('#'.$tag->text, '<a href="https://twitter.com/hashtag/'.$tag->text.'" style="text-decoration: none; "><font color="#55acee">#'.$tag->text.'</font></a>', $text);
					}

					foreach($status->entities->urls as $url){
						$text = str_replace($url->url, '<a href="'.$url->url.'" style="text-decoration: none; "><font color="#55acee">'.$url->display_url.'</font></a>', $text);
					}

					foreach($status->entities->user_mentions as $mention){
						$text = str_replace('@'.$mention->screen_name, '<a href="https://twitter.com/'.$mention->screen_name.'" style="text-decoration: none; "><font color="#55acee">@'.$mention->screen_name.'</font></a>', $text);
					}
					return $text;
				}
				function twitter_count($cnt){
					if ($cnt == 0) return '';
					return number_format($cnt);
				}
				function emb_3ds($status){
					$html = '';
					$user_url = 'https://twitter.com/'.$status->user->screen_name;
					$tweet_url = $user_url.'/status/'.$status->id_str;

					$html.='<div style="margin-bottom: 0.5em; background-color: white; border: 1px solid #e1e8ed; border-radius: 6px; ">';
						$html.='<table style="width: 100%; "><tr>';
							$html.='<td><a href="'.$user_url.'"><img src="'.$status->user->profile_image_url_https.'" style="width: 48px; border-radius: 24px; "></a></td>';
							$html.='<td style="text-align: left; text-overflow: ellipsis; "><a href="'.$user_url.'" style="font-weight: bold; text-decoration: none; "><font color="#292f33">'.htmlspecialchars($status->user->name).'</font></a><br>';
							$html.='<a href="'.$user_url.'" style="text-decoration: none; "><font color="#66757f">@'.htmlspecialchars($status->user->screen_name).'</font></a></td>';
							$html.='<td style="vertical-align top; text-align: right; "><img src="'.ROOT_URL.'img/TwitterLogo.png" style="width: 24px; "></td>';
						$html.='</tr></table>';
						$html.='<center>';
							foreach($status->entities->media as $media){
								$html.='<a href="'.$media->expanded_url.'"><img src="'.$media->media_url_https.':small" alt="'.$media->display_url.'" style="width: 95%; border: 1px solid lightgray; border-radius: 6px; "></a>';
								$status->text = str_replace($media->url, '', $status->text);
							}
						$html.='</center>';
						$html.='<p><font color="#292f33">'.twitter_text($status).'</font></p>';

						$html.='<a href="'.$tweet_url.'" style="text-decoration: none; "><font color="#66757f">'.date('h:i A - M d, Y', strtotime($status->created_at)).'</font></a><br>';
						$html.='<center><font color="#66757f"><a href="https://twitter.com/intent/tweet?in_reply_to='.$status->id_str.'"><img src="'.ROOT_URL.'img/reply.png" alt="reply" width="24px"></a>'.twitter_count($status->reply_count).'　　';
						$html.='<a href="https://twitter.com/intent/retweet?tweet_id='.$status->id_str.'"><img src="'.ROOT_URL.'img/retweet.png" alt="retweet" width="24px"></a>'.twitter_count($status->retweet_count).'　　';
						$html.='<a href="https://twitter.com/intent/like?tweet_id='.$status->id_str.'"><img src="'.ROOT_URL.'img/like.png" alt="like" width="24px"></a>'.twitter_count($status->favorite_count).'</font></center>';
					$html.='</div>';

					return $html;
				}

	function tweet($status, $subcomm, $user){	//mysql使用 (use MySQL)
		static $id = 0;
		static $s = [
			'stamp' => ['ja' => "BlueHoodスタンプ", 'en' => "BlueHoodStamp"],
		];

		$res = mysql_fetch_assoc(mysql_query("select comm_id, screen_name from tweet where id = ".$status->id));
		if ($res){
			?><div style="display: inline-block; padding: 6px; width: 240px; text-align: left; vertical-align: top; ">
				<table><tr style="vertical-align: top; "><?php
					if ($user){
						echo '<td><a href="'.ROOT_URL.'user/?'.http_build_query([screen_name => $status->user->screen_name]).'" class="a-disabled" style="color: #'.$status->user->profile_link_color.'; ">★</a></td>';
					}
					if ($res['comm_id'] && $subcomm){
						$comm_ids = explode(',',$res['comm_id']);
						if ($comm_ids != []){
							?><td><img src="<?php echo ROOT_URL; ?>img/tag.png" style="width: 0.75em; "></td><td style="word-break: break-all; "><?php
							foreach($comm_ids as $comm_id){
								if ($comm_id != COMMID_STAMP){
									$comm = mysql_fetch_assoc(mysql_throw(mysql_query("select * from comm where id = '".$comm_id."'")));
									?><a class="tweet-sub a-disabled" target="_blank" href="<?php echo ROOT_URL; ?>view/?<?php echo http_build_query(['comm_id' => $comm_id]); ?>">
										<span style="color: gray; "><?php echo $comm['name']; ?> </span>
									</a><?php
								}else{
									?><a class="tweet-sub a-disabled" target="_blank" href="<?php echo ROOT_URL; ?>view/stamp/">
                                                                                <span style="color: gray; "><?php l($s['stamp']); ?> </span>
                                                                        </a><?php
								}
							}
							?></td><?php
						}
					}
				?></tr></table>
				<?php if ((useragent() == '3ds')/*||(useragent() == 'new3ds')*/) echo emb_3ds($status); else{ ?>
					<div id="tweet-<?php echo $id; ?>"></div>
					<script>$(function(){ twttr.widgets.createTweet('<?php echo $status->id; ?>', document.getElementById('tweet-<?php echo $id++; ?>'), {lang: lang, theme: '<?php echo THEME; ?>'}); }); </script>
				<?php } ?>
			</div><?php
			return true;
		}
		return false;
	}

	function userlist($list){
		?><div style="text-align: center; "><?php
		foreach($list as $user){ ?>
			<a class="a-disabled" target="_blank" href="<?php echo ROOT_URL; ?>user/?<?php echo http_build_query(['screen_name' => $user->screen_name]); ?>"><div class="card" style="width: 240px; display: inline-block; vertical-align: top; text-align: left; ">
				<?php if(isset($user->profile_banner_url)){ ?><img src="<?php echo $user->profile_banner_url ?>" style="border-bottom: 1px solid lightgray; width: 100%; "><?php } ?>
				<div class="card-article" style="padding-top: 0; ">
					<table><tr>
						<td><img src="<?php echo $user->profile_image_url_https ?>" alt="avatar" class="avatar"></td>
						<td><?php echo $user->name ?><br><span class="disabled" style="">@<?php echo $user->screen_name ?></span></td>
					</tr></table>
					<div class="disabled" style="font-size: small; "><?php echo $user->description; ?></div>
				</div>
			</div></a>
		<?php }
		?></div><?php
	}

	function head($theme_color = null){
		?>
		<head>
			<meta charset = "UTF-8">
			<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

			<link rel="icon" href="<?php echo ROOT_URL; ?>favicon.ico">
<style>
<?php
        $t = [
		'text-color' => ['light' => '#222', 'dark' => '#ddd'],
		'background-color' => ['light' => 'whitesmoke', 'dark' => '#222'],
		'card-background' => ['light' => 'white', 'dark' => '#111'],
		'body-background' => ['light' => 'white', 'dark' => 'black'],
		'sidemenu-background' => ['light' => 'white', 'dark' => 'black'],
		'border' => ['light' => 'lightgray', 'dark' => '#444'],
		'button' => ['light' => 'orange', 'dark' => 'darkorange'],
        ];
	function t($theme){
		echo $theme[THEME];
	}
?>

body{
	background-color: <?php t($t['body-background']); ?>;
	color: <?php t($t['text-color']); ?>;
	font-family: Arial, sans-serif;
	margin: 0;
}

.main{
	padding-bottom: 1em; 
	background-color: <?php t($t['background-color']); ?>;
	min-height: 100vh;
}

.topbar{
	background: <?php if ($theme_color) echo $theme_color; else echo THEME_COLOR; ?>;
	color: <?php t($t['background-color']); ?>;
	border-bottom: 1px solid <?php t($t['border']); ?>;
}

#notification_number{
	position: absolute;
	top: -16px;
	right: 0;
	border-radius: 1em;
	padding: 0.25em 0.5em;
	color: <?php t($t['background-color']); ?>;
	background-color: <?php t($t['button']); ?>;
}

.marginleft{
	margin-left: 1em;
}
.marginright{
	margin-right: 1em;
}
.paddingright{
	padding-right: 1em;
}

.linkbutton{
	display: inline-block;
	color: <?php t($t['background-color']); ?>;
	background-color: <?php t($t['button']); ?>;
	border-radius: 1em;
	padding: 0.5em 1em;
	margin: 0.25em;
	text-decoration: none;
	cursor: pointer;
}
.a-disabled{
	text-decoration: none;
	color: inherit;
}

.sidemenu{
	z-index: 1;
	background-color: <?php t($t['sidemenu-background']); ?>;
}

#collection, #collection-nav{
	text-align: center;
}

.tweet-sub{
	font-size: small;
}

.avatar{
	border-radius: 50%;
}

.header{
	margin: 0;
	padding: 0.75em 1em;
	border-bottom: 1px solid <?php t($t['border']); ?>;
	background-color: <?php t($t['card-background']); ?>;
}

.card{
	margin: 0.2em;
	border: 1px solid <?php t($t['border']); ?>;
	border-radius: 0.25em;
	background-color: <?php t($t['card-background']); ?>;
}
a .card:hover{
	border-color: #55acee;
}
/*.card-header{
	background-color: whitesmoke;
	border-bottom: 1px solid <?php t($t['border']); ?>;
	padding: 0;
}*/
.card-article{
	padding: 0.75em 1em;
}

                        .disabled{
                                color: gray;
                        }
                        .underline{
                                border-bottom: 2px solid gold;
                        }

                        fieldset, legend{
                                background-color: <?php t($t['card-background']); ?>;
                        }
                        fieldset{
                                border: 1px solid <?php t($t['border']); ?>;
                                font-size: small;
                        }
                        legend{
                                font-size: medium;
                        }

@media screen and (min-width: 766px) {	/* Wii U, PC, タブレット (tablet) */
	.sidemenu{
		position: fixed;
		top: 0;
		left: 0;
		width: 64px;
		height: 100vh;
		border-right: 1px solid <?php t($t['border']); ?>;
		text-align: center;
	}
	.sidemenu img, .sidemenu > span, .sidemenu i{
		width: 48px;
		height: 48px;
	}
	.sidemenu > span{
		display: inline-block;
		position: relative;
	}

	.topbar{
		left: 64px;
		height: 24px;
		font-size: large;
		font-weight: bold;
		margin-top: 0;
		margin-bottom: 0;
		margin-left: 64px;
		padding: 8px;
		padding-left: 16px;
	}

	#translate{
		padding-left: 64px;
	}

	.main{
		padding-left: 64px;
	}

	.paddingleft{
		margin-left: 1em;
	}

	body{
		box-shadow: 0px 0 5px 0px rgba(128, 128, 128, 0.4);
	}
	@media screen and (min-width: 981px) {
		body{
			width: 980px;
		}
	}
}

@media screen and (max-width: 767px) {	/* 3DS, スマホ (smartphone) */
	.sidemenu{
		/*position: fixed;*/
		top: 0;
		left: 0;
		width: 100%;
		height: 32px;
		border-bottom: 1px solid <?php t($t['border']); ?>;
		text-align: center;
	}
	.sidemenu img, .sidemenu > span, .sidemenu i{
		width: 32px;
		height: 32px;
	}
	.sidemenu > span{
		display: inline-block;
		position: relative;
	}

	.topbar{
		font-size: large;
		font-weight: bold;
		margin: 0;
		padding: 4px;
	}

	.main{
	}

	.paddingleft{
		padding-left: 1em;
	}

	#translate{
	}
}
</style>
			<!-- Global Site Tag (gtag.js) - Google Analytics -->
			<script async src="https://www.googletagmanager.com/gtag/js?id=UA-106651880-1"></script>
			<script>
			  window.dataLayer = window.dataLayer || [];
			  function gtag(){dataLayer.push(arguments)};
			  gtag('js', new Date());

			  gtag('config', 'UA-106651880-1');
			</script>

			<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
			<?php if (useragent() != '3ds'){ ?><script src="https://platform.twitter.com/widgets.js" charset="utf-8"></script><?php } ?>
			<script type="text/javascript">
				var root_url = '<?php echo ROOT_URL; ?>';
				var tweet_url = root_url + 'tweet/';
				var lang = (window.navigator.languages && window.navigator.languages[0]) ||
            				window.navigator.language ||
            				window.navigator.userLanguage ||
            				window.navigator.browserLanguage;

				<?php if (useragent() == 'wiiu'){ ?>
					$(function(){
						setInterval(function(){
							$('.twitter-tweet-rendered').each(function(){
								if ($(this).height() == 0){
									$(this).css('height', '1px');
								}
							});
						}, 1000);
					});
				<?php } ?>

				$(function(){
					document.title = $('.topbar').text()+' - <?php echo SITE_NAME; ?>';
				});
			</script>
			<meta name="twitter:card" content="summary" />
			<meta name="twitter:site" content="@bluehood_admin" />
			<meta name="twitter:title" content="<?php ?><?php echo SITE_NAME; ?>" />
			<meta name="twitter:description" content="Twitter のイメージをつなげるコミュニティ。The community to link images on Twitter. " />
			<meta name="twitter:image" content="<?php echo DOMAIN.ROOT_URL; ?>img/twiverse.php" />
		</head>
		<body>
			<div class="sidemenu">
				<a href="<?php echo ROOT_URL; ?>"><img src="<?php echo ROOT_URL; ?>img/twiverse/<?php themeimg_basename(); ?>"></a>
				<a href="<?php echo ROOT_URL; ?>user/"><img class="avatar" src="<?php
					if (isset($_SESSION['twitter']['account'])) echo str_replace('normal', 'bigger', $_SESSION['twitter']['account']['user']->profile_image_url_https);
					else echo ROOT_URL.'img/nologin.png';
				?>" alt="マイページ" ></a>
				<a href="<?php echo ROOT_URL; ?>tweet/diary/"><img src="<?php echo ROOT_URL; ?>img/diary.png" alt="つぶやき投稿"></a>
				<a href="<?php echo ROOT_URL; ?>tweet/draw/"><img src="<?php echo ROOT_URL; ?>img/draw.png" alt="お絵かき投稿"></a>
				<a href="<?php echo ROOT_URL; ?>view/stamp/"><img src="<?php echo ROOT_URL; ?>img/stamp.png"></a>
				<a href="<?php echo ROOT_URL; ?>view/search/"><img src="<?php echo ROOT_URL; ?>img/comm.png" alt="コミュニティ"></a>
				<!--<span><a href="<?php echo ROOT_URL; ?>feed.php" target="_blank" style="text-decoration: none; "><img src="<?php echo ROOT_URL; ?>img/feed.png" alt="通知" style="position: absolute; top: 0; left: 0; "><?php
					if ($_SESSION['notification']){
						echo '<p id="notification_number">'.$_SESSION['notification'].'</p>';
					}
				?></a></span>-->
				<a href="<?php echo ROOT_URL; ?>user/setting.php"><img src="<?php echo ROOT_URL; ?>img/settings.png"></a>
				<a href="<?php echo ROOT_URL; ?>etc/"><img src="<?php echo ROOT_URL; ?>img/etc.png"></a>
			</div>
		<!--<?php if (useragent() != '3ds'){ ?>
			<div id="translate">
				<div id="google_translate_element"></div>
				<script type="text/javascript">
					function googleTranslateElementInit() {
					  new google.translate.TranslateElement({pageLanguage: 'ja', layout: google.translate.TranslateElement.FloatPosition.TOP_LEFT}, 'google_translate_element');
					}
				</script>
				<script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
			</div>
		<?php } ?>-->
		</body>
		<?php
	}

	function detector($soft_id){	// mysql がオープンであること。 (mysql must be open)
		$prefix = substr($soft_id, 0, 2);
		$detector = mysql_fetch_assoc(mysql_throw(mysql_query("select * from detector where prefix='".$prefix."'"))); // null もあり得る (it could be null)
		return $detector;
	}

	function make_comm($soft_id, $name){
		$id = substr(uniqid(), 0, 8);

		$twitter_admin = twitter_admin();
		$list = $twitter_admin->post('lists/create', ['name' => 'BH'.$name, 'mode' => 'public', 'description' => $name]);
		twitter_throw($list);
		$collection = $twitter_admin->post('collections/create', ['name' => 'BH'.$name, 'description' => $name, url => 'https://twiverse.net/view?comm_id='.$id, timeline_order => 'tweet_reverse_chron']);
		twitter_throw($collection);
		$collection_id = str_replace('custom-', '', $collection->response->timeline_id);

		mysql_start();
		mysql_query("insert into soft_id2name (id) values ('".$soft_id."')");
		mysql_query("update soft_id2name set name='".$name."' where id='".$soft_id."'");
		mysql_query("insert comm (id, soft_id, name, list_id, collection_id, post_n, list_n) values ('".$id."', '".$soft_id."', '".$name."', ".$list->id.", ".$collection_id.", 0, 0)");
		mysql_close();

		return $id;
	}

	function collection($id, $sort_index, $sub_comm, $user){
		$twitter = twitter_reader();
		mysql_start();

		$request = ['id' => $id, 'count' => '200'];
		if ($sort_index) $request['max_position'] = $sort_index;
		$collection = $twitter->get('collections/entries', $request);

		$show_i = 0;
		?><div style="text-align: center; "><?php
		if (empty($collection)){
			echo 'ツイートがありません。';
		}else{
			foreach($collection->response->timeline as $context){
				$status = $collection->objects->tweets->{$context->tweet->id};
				$status->user = $collection->objects->users->{$status->user->id};
				$status->sort_index = $context->tweet->sort_index;

				if ((!$status->user->protected)||(!$twitter->use_admin)){
					if (tweet($status, $sub_comm, $user)) if (++$show_i >= MAX_TWEETS) break;
					$sort_index = $status->sort_index;
				}
			}

			echo '<div style="clear: both; "></div>';
			parse_str($_SERVER['QUERY_STRING'], $query);
			$query['i'] = $sort_index;
			if ($show_i >= MAX_TWEETS) echo '<a href="?'.http_build_query($query).'"><button>もっとみる</button></a>';
		}
		?></div><?php

		mysql_close();
	}

        function helpbutton($desc){ ?>
                <img src="<?php echo ROOT_URL; ?>img/help.png" style="width: 1em; cursor: pointer; " onclick="alert('<?php echo $desc; ?>'); ">
        <?php }

        function catch_default($e){
                ?>エラーが発生しました。
		<form action="<?php echo ROOT_URL; ?>senderr.php" method="post">
			このエラーを @bluehood_admin に報告できます。<br>
			<textarea name="msg" rows="10" cols="50" readonly><?php
				echo "URL: ".$_SERVER['REQUEST_URI']."\n";
				echo "Error: ".$e->getMessage()."\n";
			?></textarea><br>
			<input type="submit">
		<form>
		<?php
		die();
        }

	function s($sentence){
		$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
		if (isset($sentence[$lang])) return $sentence[$lang];
		else return $sentence['en'];	/* 英語デフォルト (English default) */
	}

	function l($sentence){
		echo s($sentence);
	}

	function themeimg_basename(){
		if (THEME == 'light') $basename = 'default.png';
		else $basename = 'banwolf.png';
		$month = (int)date('n');
		$day = (int)date('j');
		switch($month){
			case 1:
			if (($day == 1)||($day == 2)||($day == 3)) $basename = 'newyear.png';
			break;

			case 2:
			if (($day == 3)) $basename = 'setsubun.png';
			else if (($day == 14)) $basename = 'valen.png';
			break;

			case 12:
			if (($day == 24)||($day == 25)) $basename = 'christmas.png';
			else if ($day == 31) $basename = 'newyeareve.png';
			break;
		}
		echo $basename;
	}
?>
