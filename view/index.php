<?php
	include('/var/www/twiverse.php');
	unset($_SESSION['collection_cursor']);
	//$conn = twitter_start();

	$s = [
		'community' => ['ja' => "コミュニティ", 'en' => "community", ],
		'allposts' => ['ja' => "すべての投稿", 'en' => "All posts", ],
		'twitterlist' => ['ja' => "Twitter リスト", 'en' => "Twitter list", ],
		'postdraw' => ['ja' => "お絵かきを投稿する", 'en' => "Draw to this community", ],
		'postdiary' => ['ja' => "つぶやきを投稿する", 'en' => "Tweet to this community", ],
		//'' => ['ja' => "", 'en' => "", ],
	];

	//if (!isset($_GET['comm_id'])) header('Location: '.DOMAIN.ROOT_URL);

	if (isset($_GET['comm_id'])){
		mysql_start();
		$res = mysql_fetch_assoc(mysql_query("select name,collection_id,list_id from comm where id = '".$_GET['comm_id']."'"));
		if (isset($res['name'])){
			$comm_name = $res['name'].' '.s($s['community']);
			$collection_id = $res['collection_id'];
			$list_id = $res['list_id'];
		}
		mysql_close();
	}

	if (!isset($comm_name)){
		$comm_name = s($s['allposts']);
		$collection_id = ALL_POSTS;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel = "stylesheet" type = "text/css" href = "view.css">

		<!--<meta name="twitter:card" content="summary" />
		<meta name="twitter:site" content="@Twiverse_admin" />
		<meta name="twitter:title" content="<?php
			echo $comm_name;
		?> - Twiverse" />
		<meta name="twitter:description" content="Twitterを活用したUniversalゲームコミュニティ" />
		<meta name="twitter:image" content="<?php echo DOMAIN.ROOT_URL; ?>twiverse.png" />-->
	</head>
	<?php head(); ?>
	<body>
		<div class="topbar"><?php
			echo $comm_name;
		?></div>
		<div class="main">
			<div class="header">
			<!--<a href="https://twitter.com/share" class="twitter-share-button" data-size="large" data-hashtags="Twiverse">Tweet</a> <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>-->
			<?php if (isset($list_id)){ ?>
				<a href="list.php?<?php echo http_build_query(['comm_id' => $_GET['comm_id']]); ?>"><?php l($s['twitterlist']); ?></a>
			<?php } ?>
			<input type="button" onclick="location.href=tweet_url+'diary/?<?php echo http_build_query(['comm_id' => $_GET['comm_id']]); ?>'" value="<?php l($s['postdiary']); ?>" style="float: right; ">
			<input type="button" onclick="location.href=tweet_url+'draw/?<?php echo http_build_query(['comm_id' => $_GET['comm_id']]); ?>'" value="<?php l($s['postdraw']); ?>" style="float: right; ">
			<div style="clear: both; "></div>
			</div>

			<?php collection('custom-'.$collection_id, $_GET['i'], !isset($_GET['comm_id']), true); ?>
		</div>
	</body>
</html>
