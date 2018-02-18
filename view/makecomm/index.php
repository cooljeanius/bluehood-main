<?php
	include('/var/www/twiverse.php');

	$s = [
		'title' => ['ja' => "コミュニティ登録", 'en' => "Register communities", ],
		'header' => [
			'ja' => "一部のディテクターのコミュニティは、このページで作成できます。",
			'en' => "The communities of some detectors can be established at this page. "
		],

		'wiiu' => ['ja' => "Wii U ディテクター", 'en' => "Wii U detector", ],
		'sc' => ['ja' => "スクリーンショット", 'en' => "Screenshot", ],
		'sc_note' => ['ja' => "スクリーンショットは Wii U ブラウザより送信してください。", 'en' => "Please send the screenshots from Wii U Browser. ", ],
		'soft_name' => ['ja' => "ソフト名", 'en' => "Software name", ],
		'3ds' => ['ja' => "3DS ディテクター", 'en' => "3DS detector", ],

		'report' => ['ja' => "その他のコミュニティ操作", 'en' => "Report to establish more communities, or edit communities data", ],
		//'' => ['ja' => "", 'en' => "", ],
	];
?>
<!DOCTYPE html>
<html>
	<?php head(); ?>
	<body>
		<div class="topbar"><?php l($s['title']); ?></div>
		<div class="main">
			<div class="header">
			<?php l($s['header']); ?><br>
			<a href="<?php echo ROOT_URL; ?>view/report/"><?php l($s['report']); ?></a><br>
			</div>
			<br>
			<form action="WU.php" method="post" enctype="multipart/form-data" class="marginleft marginright">
				<fieldset>
					<legend><?php l($s['wiiu']); ?></legend>
					<?php l($s['sc']); ?>: <input name="img" type="file" accept="image/jpeg"><br>
					<?php l($s['sc_note']); ?><br>
					<?php l($s['soft_name']); ?>: <input name="name" type="text"><br>
					<input class="submit" type="submit" onclick="$('.submit').prop('disabled', true); $('.submit').attr('value', '処理中…'); submit(); ">
				</fieldset>
			</form>
			<form action="3D.php" method="post" enctype="multipart/form-data" class="marginleft marginright">
				<fieldset>
					<legend><?php l($s['3ds']); ?></legend>
					<?php l($s['sc']); ?>: <input name="img" type="file" accept="image/jpeg"><br>
					<?php l($s['soft_name']); ?>: <input name="name" type="text"><br>
					<input class="submit" type="submit" onclick="$('.submit').prop('disabled', true); $('.submit').attr('value', '処理中…'); submit(); ">
				</fieldset>
			</form>
		</div>
	</body>
</html>
