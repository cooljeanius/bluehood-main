<?php
	include('/var/www/twiverse.php');
	$s = [
		'adminpg' => ['ja' => "管理ページ", 'en' => "Administration page", ],
		'accessperms' => [
			'ja' => "このページは管理者のみアクセス可能でなくてはなりません。<br>
			.htaccess を正しく設定してください。<br>",
			'en' =>
			"This page must be accessible only by the administrator. <br>
			Please set up .htaccess correctly. <br>", 
		],
		'swreg' => [
			'ja' => "ソフト登録 &amp; コミュニティ作成",
			'en' => "Software registration &amp; community creation",
		],
		'soft_id' => ['ja' => "ソフト ID: ", 'en' => "Software ID: ", ],
		'name' => ['ja' => "名前: ", 'en' => "Name: ", ],
		//'' => ['ja' => "", 'en' => "", ],
	];
?>

<!DOCTYPE html>
<html>
	<?php head(); ?>
	<body>
		<h2 class="topbar"><?php l($s['adminpg']); ?></h2>
		<div class="main paddingleft paddingright">
			<?php l($s['accessperms']); ?>
			<br>
			<form method="post" action="makecomm.php">
				<fieldset>
					<legend><?php l($s['swreg']); ?></legend>
					<?php l($s['soft_id']); ?><input name="soft_id" type="text">
					<?php l($s['name']); ?><input name="name" type="text">
					<input type="submit">
				</fieldset>
			</form>
		</div>
	</body>
</html>
