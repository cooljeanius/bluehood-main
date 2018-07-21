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
		'soft_id' => ['ja' => "ソフト ID: ", 'en' => "software ID: ", ],
		'name' => ['ja' => "名前: ", 'en' => "name: ", ],
		//'' => ['ja' => "", 'en' => "", ],
	];
?>

<!DOCTYPE html>
<html>
	<?php head(); ?>
	<body>
		<h2 class="topbar">管理ページ</h2>
		<div class="main paddingleft paddingright">
			このページは管理者のみアクセス可能でなくてはなりません。<br>
			.htaccess を正しく設定してください。<br>
			<br>
			<form method="post" action="makecomm.php">
				<fieldset>
					<legend>ソフト登録 &amp; コミュニティ作成</legend>
					ソフト ID: <input name="soft_id" type="text">
					名前: <input name="name" type="text">
					<input type="submit">
				</fieldset>
			</form>
		</div>
	</body>
</html>
