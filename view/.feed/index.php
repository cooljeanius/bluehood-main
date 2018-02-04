<?php
	include('/var/www/twiverse.php');
	$twitter = twitter_start();
	mysql_start();
?>
<!DOCTYPE html>
<html lang = "ja">
	<head>
		<meta charset = "UTF-8">
		<link rel = "stylesheet" type = "text/css" href = "view.css">

		<meta name="twitter:card" content="summary" />
		<meta name="twitter:site" content="@Twiverse_admin" />
		<meta name="twitter:title" content="Twiverse" />
		<meta name="twitter:description" content="Twitterを活用したUniversalゲームコミュニティ" />
		<meta name="twitter:image" content="<?php echo DOMAIN.ROOT_URL; ?>view/topic.png" />
	</head>
	<body>
		<?php include(ROOT_PATH.'header.php'); ?>
		<div class="topbar">みんなの投稿</div>
		<div class="main">
			<?php
				/*$timeline = $twitter->get('statuses/home_timeline', ['count' => '200']);
				foreach($timeline as $status){
					echo $status->text.'<br>';
					$res = mysql_fetch_assoc(mysql_query("select emb_def from tweet where id = ".$status->id));
					if ($res){
						echo '<div style="display: inline-block; margin: 4px;">';
						echo $res['emb_def'];
						echo '</div>';
					}
				}*/
			?>
		</div>
	</body>
</html>
