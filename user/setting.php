<?php
	include('/var/www/twiverse.php');
	unset($_SESSION['collection_cursor']);
	$s = [
		'title' => ['ja' => '設定', 'en' => 'Settings'],
		//'' => ['ja' => '', 'en' => ''],
	];
	$twitter = twitter_start();
	mysql_start();
	$set = mysql_fetch_assoc(mysql_query("select draw_width, draw_height, draw_autosave from user where screen_name='".$_SESSION['twitter']['screen_name']."'"));
	mysql_close();
?>
<!DOCTYPE html>
<html>
	<head>
		<style>
			fieldset, legend{
				background-color: white;
			}
			fieldset{
				border: 1px solid lightgray;
			}
		</style>
	</head>
	<?php head('#'.$_SESSION['twitter']['account']['user']->profile_link_color); ?>
	<body>
		<div class="topbar"><?php l($s['title']); ?></div>
		<div class="main paddingleft paddingright">
			<form method="post" action="upset.php">
				<fieldset>
					<legend>お絵かき</legend>
					<input name="draw_autosave" type="checkbox" <?php if ($set['draw_autosave']) echo 'checked'; ?>>1 分ごとに自動で下書き保存する<br>
					キャンバスの横サイズ <input name="draw_width" type="number" value="<?php echo $set['draw_width']; ?>" style="width: 4em; "> px<br>
					キャンバスの縦サイズ <input name="draw_height" type="number" value="<?php echo $set['draw_height']; ?>"  style="width: 4em; "> px<br>
				</fieldset>
				<input type="submit">
			</from>
		</div>
	</body>
</html>

