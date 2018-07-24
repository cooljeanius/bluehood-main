<?php
	include('/var/www/twiverse.php');
	unset($_SESSION['collection_cursor']);
	$s = [
		'title' => ['ja' => '設定', 'en' => 'Settings'],
		'theme' => ['ja' => "サイトテーマ", 'en' => "Site theme", ],
		'autotheme' => [
			'ja' => "昼と夜に自動的に切り替える (日本時間準拠)",
			'en' => "Automatically switch theme between day and night (based on Japan time)",
		],
		'lighttheme' => [
			'ja' => "昼間モード (BlueHood)", 'en' => "Daytime mode (BlueHood)",
		],
		'darktheme' => [
			'ja' => "夜間モード (BanWolf)", 'en' => "Night mode (BanWolf)",
		],
		'post' => ['ja' => "投稿", 'en' => "Post", ],
		'dispposts' => ['ja' => "投稿を BlueHood に表示する", 'en' => "Display posts on BlueHood", ],
		'dispposts2' => [
			'ja' => "チェックされているときに行った投稿は、BlueHood に登録・表示されます。",
			'en' => "Posts made when this box is checked are registered and displayed on BlueHood.",
		],
		'drawing' => ['ja' => "お絵かき", 'en' => "Drawing", ],
		'canvwidth' => ['ja' => "キャンバスの横サイズ ", 'en' => "canvas width", ],
		'canvwidthamt' => [
			'ja' => " px (0~440 デフォルト: 320)",
			'en' => " px (0 to 440; default: 320)",
		],
		'canvheight' => ['ja' => "キャンバスの縦サイズ ", 'en' => "canvas height", ],
		'canvheightamt' => [
			'ja' => " px (0~440 デフォルト: 120)",
			'en' => " px (0 to 440; default: 120)",
		],
		'canvdisclaimer' => [
			'ja' => "キャンバスの大きさを変更すると、下書きの一部情報が失われる場合があります。",
			'en' => "Changing the size of the canvas may result in the loss of some draft information.",
		],
		'attachments' => [
			'ja' => "添付画像とお絵かきを",
			'en' => "Attached image and drawing",
		],
		'mergev' => ['ja' => "縦に結合する", 'en' => "Merge vertically", ],
		'separate' => ['ja' => "別々にする", 'en' => "Separate", ],
		'detector' => ['ja' => "ディテクター", 'en' => "Detector", ],
		'aboutdetectors' => [
			'ja' => "ディテクターは、添付画像を自動認識してコミュニティに振り分けるプログラムです。たとえば、3DS ディテクターは 3DS のスクリーンショットを認識します。",
			'en' => "Detectors are programs that automatically recognize attached images and distribute them to their corresponding communities. For example, 3DS detectors recognize screenshots sent from a 3DS.",
		],
		'strength' => ['ja' => "有効", 'en' => "effectiveness", ],
		'makelist' => ['ja' => "リスト登録", 'en' => "Create list", ],
		'aboutlist' => [
			'ja' => "チェックをつけると、投稿先コミュニティの Twitter リストに自動的に登録されます。",
			'en' => "When checked, posts will be registered automatically in the Twitter list for the posting community.",
		],
		'stamp' => ['ja' => "スタンプ", 'en' => "stamp", ],
		'3Dness' => ['ja' => "3Dデータ 高さ ", 'en' => "3D data height ", ],
		'about3D' => [
			'ja' => "3D データにおけるスタンプの持ち手の長さです。\nたとえば、2 mm に設定すると刻印面だけの薄いスタンプになります。\n21 mm に設定すると、持ち手がある標準的なスタンプになります。",
			'en' => "The length of the handle of the stamp in 3D data. For example, if it is set to 2mm, the printed stamp will be a thin stamp with only an engraved surface. If set to 21mm, the printed stamp will be a standard stamp with a handle.",
		],
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
				<fieldset>
					<legend>スタンプ</legend>
					3Dデータ 高さ <input name="stamp_height" type="number" value="<?php echo $set['stamp_height']; ?>" min="2" max="21" style="width: 4em; "> mm (2~21)<?php helpbutton('3D データにおけるスタンプの持ち手の長さです。\nたとえば、2 mm に設定すると刻印面だけの薄いスタンプになります。\n21 mm に設定すると、持ち手がある標準的なスタンプになります。'); ?><br>
				</fieldset>
				<input type="submit">
			</from>
		</div>
	</body>
</html>

