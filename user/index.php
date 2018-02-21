<?php
	include('/var/www/twiverse.php');
	unset($_SESSION['collection_cursor']);
	$s = [
		'logout' => ['ja' => 'ログアウト', 'en' => 'Log out'],
		'setting' => ['ja' => '設定', 'en' => 'Settings'],
		'mypage' => ['ja' => 'マイページ', 'en' => 'My Page'],
		'profile' => ['ja' => ' さんのプロフィール', 'en' => "'s Profile"],
		'cnt_tweets' => ['ja' => 'ツイート', 'en' => 'Tweets'],
		'cnt_likes' => ['ja' => 'いいね', 'en' => 'Likes'],
		'cnt_followings' => ['ja' => 'フォロー', 'en' => 'Followings'],
		'cnt_followers' => ['ja' => 'フォロワー', 'en' => 'Followers'],
		//'' => ['ja' => '', 'en' => ''],
	];

	$myname = $_SESSION['twitter']['account']['user']->screen_name;
	if (!isset($_GET['screen_name'])){
		$twitter = twitter_start();
		header( 'location: '. DOMAIN.ROOT_URL.'user/?'.http_build_query(['screen_name' => $myname]));
	}
	$twitter = twitter_reader();

	$screen_name = $_GET['screen_name'];
	$ismypage = ($screen_name == $myname);
	$user = $twitter->get('users/show', ['screen_name' => $screen_name]);
?>
<!DOCTYPE html>
<html>
	<head>
		<style>
			.profile{
				background-image: url('<?php echo $user->profile_banner_url; ?>');
				background-size: contain;
				background-position: top;
				background-repeat: no-repeat;
			}
			.profile-article{
				text-align: center;
			}
			.profile-user{
			}
			.profile-content{
				text-align: left;
				font-size: small;
			}
			@media screen and (min-width: 766px) {
				.profile-article .card{
					vertical-align: top;
					background-color: rgba(255, 255, 255, 0.6);
				}
				.profile-article .disabled{
					color: inherit;
				}
				.profile-user{
					text-align: center;
					width: 30%;
					display: inline-block;
				}
				.profile-user .avatar{
					width: 80%;
				}
				.profile-user-sm{
					display: inline;
				}
				.profile-content{
					width: 40%;
					display: inline-block;
				}
			}
			@media screen and (max-width: 765px) {
				.profile-user{
					text-align: left;
				}
				.profile-user .avatar{
					width: 40%;
					float: left;
				}
				.profile-user-pc{
					display: inline;
				}
			}
		</style>
	</head>
	<?php head('#'.$user->profile_link_color); ?>
	<body>
		<script type="text/javascript" src="user.js"></script>
		<div class="topbar"><?php
			if ($ismypage) echo '<span>マイページ</span>';
			else echo $user->name.'<span>さんのプロフィール</span>';
		?></div>
		<div class="main">
			<?php if ($ismypage){
				echo '<div class="header"><a class="marginleft" href="'.ROOT_URL.'logout.php"><button>'.s($s['logout']).'</button></a>';
				echo '<a href="setting.php"><button>'.s($s['setting']).'</button></a></div>';
			}?>
			<div class="profile">
				<div class="profile-article">
					<a target="_blank" href="https://twitter.com/<?php echo $user->screen_name; ?>" class="a-disabled"><div class="card profile-user"><div class="card-article">
						<img src="<?php echo str_replace('_normal', '', $user->profile_image_url_https); ?>" class="avatar">
						<div class="profile-user-pc"></div>
						<?php echo $user->name ?> <span class="disabled">@<?php echo $user->screen_name ?></span>
						<div class="disabled" style="font-size: small; ">
							<div>ツイート <?php echo $user->statuses_count ?>　<div class="profile-user-sm"></div>いいね <?php echo $user->favourites_count ?></div>
							<div>フォロー <?php echo $user->friends_count ?>　<div class="profile-user-sm"></div>フォロワー <?php echo $user->followers_count ?></div>
						</div>
						<div style="clear: both; "></div>
					</div></div></a>
					<div class="profile-content">
						<div class="marginleft marginright card">
							<div class="card-article"><?php echo nl2br(htmlspecialchars($user->description)); ?></div>
						</div>
					</div>
				</div>
			</div>
				<?php try{
					if ($ismypage){
        		        	        if (isset($_SESSION['twitter']['account']['collection_id'])){
							collection($_SESSION['twitter']['account']['collection_id'], $_GET['i'], true, false);
						}else{
							echo '<br><div class="whitebox marginleft marginright"><p>あなたのTwitterアカウントに「Twiverse」コレクションを作成しませんか？<br>作成すると、「自分の投稿」がここに表示されます！<br><br>※「自分の投稿」は公開されます。</p>';
							echo '<a class="linkbutton" href="makecoll.php" onclick="$(this).hide(); ">作成する</a></div>';
						}
					}else{
						mysql_start();
						$res = mysql_fetch_assoc(mysql_throw(mysql_query("select collection_id from user where id=".$user->id)));
						mysql_close();
						if ($res['collection_id']){
							collection('custom-'.$res['collection_id'], $_GET['i'], true, false);
						}
					}
				}catch(Exception $e){ catch_default($e); } ?>
		</div>
	</body>
</html>

