<?php
	include('/var/www/twiverse.php');
	$s = [
		'3DS' => ['ja' => "3DSからの投稿", 'en' => "Posting from 3DS", ],
		'gamememo' => [
			'ja' => "ゲームメモでかいた絵を投稿する&#13;&#10; ",
			'en' => "Post a drawing made in Game Notes",
		],
		'uacheck' => [
			'ja' => "Wii U版お絵かきで投稿する&#13;&#10;旧3DSでは動作しません。",
			//according to Ray, this is a paraphrase, bc talking about the Wii U interface seems unnecessary in the 3DS section:
			'en' => "Posting drawings to BlueHood is not compatible with the old 3DS."
		],
		'stamp' => [
			'ja' => "スタンプを投稿する&#13;&#10;試用版", 
			'en' => "Post a stamp (Trial version)",
		],
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
		<div class="topbar"><?php l($s['3DS']); ?></div>
		<div class="main">
			<center>
				<input type="button" value="ゲームメモでかいた絵を投稿する&#13;&#10; " style="width: 320px; " onclick="location.href = 'gamememo/?<?php echo $query; ?>'; ">
				<input type="button" value="Wii U版お絵かきで投稿する&#13;&#10;旧3DSでは動作しません。" style="width: 320px; " onclick="location.href = 'draw/?<?php echo $query; ?>'; ">
				<input type="button" value="スタンプを投稿する&#13;&#10;試用版" style="width: 320px; " onclick="location.href = '../stamp/'; ">
			</center>
		</div>
	</body>
</html>
