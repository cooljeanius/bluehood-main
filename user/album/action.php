<?php
	include('/var/www/twiverse.php');
	$s = [
		'album' => ['ja' => "アルバムを使う", 'en' => "Use the album", ],
		'posttweets' => ['ja' => "つぶやきを投稿する", 'en' => "Post tweets", ],
		'postdrawing' => ['ja' => "お絵かきを投稿する", 'en' => "Post a drawing", ],
		'deletionprompt' => [
			'ja' => "本当に削除しますか？",
			'en' => "Do you really want to delete this?",
		],
		'delete' => ['ja' => "削除", 'en' => "Delete", ],
		//'' => ['ja' => "", 'en' => "", ],
	];
	//unset($_SESSION['collection_cursor']);
	twitter_start();
?>

<!DOCTYPE html>
<html>
	<head>
	</head>
	<?php head(); ?>
	<body>
		<?php include(ROOT_PATH.'header.php'); ?>
		<h2 class="topbar"><?php l($s['album']); ?></h2>
		<div class="main" style="text-align: center; ">
			<img src="<?php echo htmlspecialchars($_GET['img']); ?>" style="max-height: 70vh; max-width: 90%; -webkit-filter: drop-shadow(2px 2px 2px rgba(128, 128, 128, 0.4)); "><br>
			<a href="<?php echo ROOT_URL; ?>tweet/diary/?<?php echo http_build_query(['album' => $_GET['id']]); ?>" class="a-disabled"><div class="card" style="display: inline-block; "><div class="card-article"><?php l($s['posttweets']); ?></div></div></a>
			<a href="<?php echo ROOT_URL; ?>tweet/draw/?<?php echo http_build_query(['album' => $_GET['id']]); ?>" class="a-disabled"><div class="card" style="display: inline-block; "><div class="card-article"><?php l($s['postdrawing']); ?></div></div></a>
			<div class="card" style="display: inline-block; cursor: pointer; " onclick="if (confirm('本当に削除しますか？')) window.location = 'remove.php?<?php echo http_build_query(['album' => $_GET['id']]); ?>'; "><div class="card-article">削除</div></div>
		</div>
	</body>
</html>
