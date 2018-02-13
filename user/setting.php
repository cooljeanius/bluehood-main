<?php
	include('/var/www/twiverse.php');
	unset($_SESSION['collection_cursor']);
	$s = [
		'title' => ['ja' => '設定', 'en' => 'Settings'],
		//'' => ['ja' => '', 'en' => ''],
	];
	$twitter = twitter_start();
	mysql_start();
	$set = mysql_fetch_assoc(mysql_query("select * from user where screen_name='".$_SESSION['twitter']['screen_name']."'"));
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
				font-size: small;
			}
			legend{
				font-size: medium;
			}
		</style>
	</head>
	<?php head('#'.$_SESSION['twitter']['account']['user']->profile_link_color); ?>
	<body>
		<div class="topbar"><?php l($s['title']); ?></div>
		<div class="main paddingleft paddingright">
			<form method="post" action="upset.php">
				<fieldset>
					<legend>投稿</legend>
					<input name="post_register" type="checkbox" <?php if ($set['post_register']) echo 'checked'; ?>>投稿を BlueHood に表示する<br>
					チェックされているときに行った投稿は、BlueHood に登録・表示されます。<br>
					<br>
					<fieldset>
						<legend>お絵かき</legend>
						<input name="draw_autosave" type="checkbox" <?php if ($set['draw_autosave']) echo 'checked'; ?>>1 分ごとに自動で下書き保存する<br>
						<br>
						キャンバスの横サイズ <input name="draw_width" type="number" value="<?php echo $set['draw_width']; ?>" style="width: 4em; "> px<br>
						キャンバスの縦サイズ <input name="draw_height" type="number" value="<?php echo $set['draw_height']; ?>"  style="width: 4em; "> px<br>
						キャンバスの大きさを変更すると、下書きの一部情報が失われる場合があります。<br>
						<br>
						添付画像とお絵かきを<br>
						<input type="radio" name="draw_sc" value="vertical" <?php if ($set['draw_sc'] == 'vertical') echo 'checked'; ?>>縦に結合する
						<input type="radio" name="draw_sc" value="separate" <?php if ($set['draw_sc'] == 'separate') echo 'checked'; ?>>別々にする<br>
					</fieldset>
				</fieldset>
				<input type="submit">
			</from>
		</div>
	</body>
</html>

