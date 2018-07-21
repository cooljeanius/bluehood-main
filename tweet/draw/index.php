<?php
	include('/var/www/twiverse.php');
	$s = [
		//'' => ['ja' => "", 'en' => "", ],
	];
	$conn = twitter_start();
	$query = $_SERVER['QUERY_STRING'];
	if ((useragent() != '3ds')&&(useragent() != 'new3ds')){
		header( 'location: '. DOMAIN.ROOT_URL.'tweet/draw/draw/?'.$query);
		die();
	}
	//header( 'location: '. DOMAIN.ROOT_URL.'tweet/draw/gamememo/?'.$query);
?>
<!DOCTYPE html>
<html lang = "ja">
	<head>
		<meta charset = "UTF-8">
	</head>
	<?php head(); ?>
	<body>
		<div class="topbar">3DSからの投稿</div>
		<div class="main">
			<center>
				<input type="button" value="ゲームメモでかいた絵を投稿する&#13;&#10; " style="width: 320px; " onclick="location.href = 'gamememo/?<?php echo $query; ?>'; ">
				<input type="button" value="Wii U版お絵かきで投稿する&#13;&#10;旧3DSでは動作しません。" style="width: 320px; " onclick="location.href = 'draw/?<?php echo $query; ?>'; ">
				<input type="button" value="スタンプを投稿する&#13;&#10;試用版" style="width: 320px; " onclick="location.href = '../stamp/'; ">
			</center>
		</div>
	</body>
</html>
