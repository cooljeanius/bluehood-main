<?php
	include('/var/www/twiverse.php');
	unset($_SESSION['collection_cursor']);

	$myname = $_SESSION['twitter']['account']['user']->screen_name;
	if (!isset($_GET['screen_name'])){
		$twitter = twitter_start();
		header( 'location: '. DOMAIN.ROOT_URL.'user/?'.http_build_query(['screen_name' => $myname]));
	}
	$twitter = twitter_reader();

	$screen_name = $_GET['screen_name'];
	$ismypage = ($screen_name == $myname);
	$user = $twitter->get('users/show', ['screen_name' => $screen_name]);
	define('THEME_COLOR', '#'.$user->profile_link_color);
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel = "stylesheet" type = "text/css" href = "user.css">

		<meta name="twitter:card" content="summary" />
		<meta name="twitter:site" content="@Twiverse_admin" />
		<meta name="twitter:title" content="<?php echo $user->name.'(@'.$user->screen_name.')さんのプロフィール'; ?> - Twiverse" />
		<meta name="twitter:description" content="Twitterを活用したUniversalゲームコミュニティ" />
		<meta name="twitter:image" content="<?php echo DOMAIN.ROOT_URL; ?>twiverse.png" />
	</head>
	<?php head(); ?>
	<body>
		<?php include(ROOT_PATH.'header.php'); ?>
		<script type="text/javascript" src="user.js"></script>
		<div class="topbar"><?php
			if ($ismypage) echo '<span>マイページ</span>';
			else echo $user->name.'<span>さんのプロフィール</span>';
		?></div>
		<div class="main">
			<?php if ($ismypage) echo '<a class="marginleft" href="'.ROOT_URL.'logout.php"><button lang="ja">ログアウト</button><button lang="en">logout</button></a>'; ?>
			<header>
				<div class="marginleft" style="padding-top: 1em; padding-bottom: 1em; ">
					<div class="marginright" style="width: 25%; float: right; text-align: center; ">
						<?php if (isset($user->profile_banner_url)){ ?>
							<a href="https://twitter.com/<?php echo $user->screen_name; ?>"><img src="<?php echo $user->profile_banner_url ?>" alt="bannar" style="width: 100%; border: 1px solid lightgray; border-radius: 6px; "></a><br>
						<?php } ?>
					</div>
					<a href="https://twitter.com/<?php echo $user->screen_name; ?>" style="color: inherit; text-decoration: none; ">
						<img src="<?php echo $user->profile_image_url_https ?>" class="avatar" alt="avatar" style="float: left; margin-right: 0.5em; ">
						<?php echo $user->name ?><br>
						<font color="#66757f">@<?php echo $user->screen_name ?></font>
					</a>
					<?php if ($ismypage){ ?>
						<form id="bannarform" action="bannarup.php" method="post" enctype="multipart/form-data">
							<p lang="ja">ヘッダー画像を変更する</p>
							<p lang="en">Change the header image</p>
							<input id="bannarimg" name="bannarimg" type="file" accept="image/*">
						</form>
					<?php }else{ ?>
						<a href="https://twitter.com/<?php echo $user->screen_name; ?>" class="twitter-follow-button" data-show-count="false" data-size="large" data-show-screen-name="false">Follow @<?php echo $user->screen_name; ?></a> <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script><br>
					<?php } ?>
					<span lang="ja">プロフィールを共有</span><span lang="en">Share this profile</span><a href="https://twitter.com/share" class="twitter-share-button" data-size="large" data-hashtags="Twiverse">Tweet</a> <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
					<div style="clear: both; "></div>
				</div>
				<div id="userstat">
					<li><span lang="ja">ツイート</span><span lang="en">Tweets</span> <div class="count"><?php echo $user->statuses_count ?></div></li>
					<li><span lang="ja">いいね</span><span lang="en">Favorites</span> <div class="count"><?php echo $user->favourites_count ?></div></li>
					<li class="clickable" onclick="$('article').load('follow.php?screen_name=<?php echo $user->screen_name; ?>'); "><span lang="ja">フォロー</span><span lang="en">Followings</span> <div class="count"><?php echo $user->friends_count ?></div></li>
					<li class="clickable" onclick="$('article').load('follower.php?screen_name=<?php echo $user->screen_name; ?>'); "><span lang="ja">フォロワー</span><span lang="en">Followers</span> <div class="count"><?php echo $user->followers_count ?></div></li>
					<div style="clear: both; "></div>
				</div>
			</header>
			<article>
				<div class="marginleft marginright" style="background-color: white; border: 1px solid lightgray; border-radius: 1em; padding: 0 0.5em; ">
					<p style="background-color: whitesmoke; border-radius: 0.5em; padding: 0.5em; ">
					<?php
						$fixed_desc = $user->description;
						$fixed_desc = str_replace("\n", '<br>', $fixed_desc);
						echo $fixed_desc;
					?></p>
					<!--<ul>
					<li>国 <?php echo $user->location ?></li>
					<li>誕生日</li>
					<li>言語 <?php echo $user->lang ?></li>
					<li>サイト <?php echo $user->url ?></li>
					</ul>-->
				</div>
				<?php
                        $list = $twitter->get('collections/list', ['screen_name' => $user->screen_name, 'count' => 200])->objects->timelines;
                        unset($collection_id);
                        foreach($list as $id => $collection){
                                if ($collection->name == 'Twiverse'){
                                        $collection_id = $id;
                                        break;
                                }
                        }
                        if (isset($collection_id)){
				collection($collection_id, $_GET['i'], true, false);
			}else{
				if ($ismypage){
					echo '<br><div class="whitebox marginleft marginright"><p>あなたのTwitterアカウントに「Twiverse」コレクションを作成しませんか？<br>作成すると、「自分の投稿」がここに表示されます！<br><br>※「自分の投稿」は公開されます。</p>';
					echo '<a class="linkbutton" href="makecoll.php" onclick="$(this).hide(); ">作成する</a></div>';
				}
			}
				?>
			</article>
		</div>
	</body>
</html>

