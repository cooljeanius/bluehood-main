<?php
	include('/var/www/twiverse.php');
	unset($_SESSION['collection_cursor']);
	$s = [
		'title' => ['ja' => '設定', 'en' => 'Settings'],
		//'' => ['ja' => '', 'en' => ''],
	];
	$twitter = twitter_start();
	mysql_start();
	$set = mysql_fetch_assoc(mysql_query("select * from user where id=".$_SESSION['twitter']['id']));
	mysql_close();
?>
<!DOCTYPE html>
<html>
	<?php head('#'.$_SESSION['twitter']['account']['user']->profile_link_color); ?>
	<body>
		<div class="topbar"><?php l($s['title']); ?></div>
		<div class="main paddingleft paddingright">
			<form method="post" action="upset.php">
				<fieldset>
					<legend>サイトテーマ</legend>
					<input name="theme" type="radio" value="auto" <?php if ($set['theme'] == 'auto') echo 'checked'; ?>>昼と夜に自動的に切り替える (日本時間準拠)
					<input name="theme" type="radio" value="light" <?php if ($set['theme'] == 'light') echo 'checked'; ?>>昼間モード (BlueHood)
					<input name="theme" type="radio" value="dark" <?php if ($set['theme'] == 'dark') echo 'checked'; ?>>夜間モード (BanWolf)
				</fieldset>
				<fieldset>
					<legend>投稿</legend>
					<input name="post_register" type="checkbox" <?php if ($set['post_register']) echo 'checked'; ?>>投稿を BlueHood に表示する<br>
					チェックされているときに行った投稿は、BlueHood に登録・表示されます。<br>
					<br>
					<fieldset>
						<legend>お絵かき</legend>
						<input name="draw_autosave" type="checkbox" <?php if ($set['draw_autosave']) echo 'checked'; ?>>1 分ごとに自動で下書き保存する (サーバー通信が発生します)<br>
						<br>
						キャンバスの横サイズ <input name="draw_width" type="number" value="<?php echo $set['draw_width']; ?>" min="0" max="440" style="width: 4em; "> px (0~440 デフォルト: 320)<br>
						キャンバスの縦サイズ <input name="draw_height" type="number" value="<?php echo $set['draw_height']; ?>" min="0" max="440" style="width: 4em; "> px (0~440 デフォルト: 120)<br>
						キャンバスの大きさを変更すると、下書きの一部情報が失われる場合があります。<br>
						<br>
						添付画像とお絵かきを<br>
						<input type="radio" name="draw_sc" value="vertical" <?php if ($set['draw_sc'] == 'vertical') echo 'checked'; ?>>縦に結合する
						<input type="radio" name="draw_sc" value="separate" <?php if ($set['draw_sc'] == 'separate') echo 'checked'; ?>>別々にする<br>
					</fieldset>
				</fieldset>
				<fieldset>
					<legend>ディテクター<?php helpbutton("ディテクターは、添付画像を自動認識してコミュニティに振り分けるプログラムです。たとえば、3DS ディテクターは 3DS のスクリーンショットを認識します。"); ?></legend>
					<table style="text-align: center; ">
						<tr><td></td> <td>有効</td> <td>リスト登録<?php helpbutton("チェックをつけると、投稿先コミュニティの Twitter リストに自動的に登録されます。"); ?></td></tr>
						<?php try{
							mysql_start();
							$detectors = mysql_throw(mysql_query("select prefix, name from detector"));
							mysql_close();
							while($detector = mysql_fetch_assoc($detectors)){
								?><tr><td><?php echo $detector['name']; ?></td> <td><input name="en_<?php echo $detector['prefix']; ?>" type="checkbox" <?php if ($set['en_'.$detector['prefix']]) echo 'checked'; ?>></td> <td><input name="list_<?php echo $detector['prefix']; ?>" type="checkbox" <?php if ($set['list_'.$detector['prefix']]) echo 'checked'; ?>></td></tr><?php
							}
						}catch(Exception $e){catch_default($e); } ?>
					</table>
				</fieldset>
				<input type="submit">
			</from>
		</div>
	</body>
</html>

