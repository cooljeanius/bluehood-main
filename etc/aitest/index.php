<?php include('/var/www/twiverse.php'); ?>

<!DOCTYPE html>
<html lang = "ja">
	<?php head(); ?>
	<body>
		<h2 class="topbar">画像認識テスト</h2>
		<div class="main paddingleft paddingright">
			<a href="https://cloud.google.com/vision/">Google Cloud Vision API</a> を利用して、画像から #ハッシュタグ を自動生成するテストページです。
			<form action="aitest.php" method="post" enctype="multipart/form-data">
				<input name="selimg" type="file" accept="image/jpeg"><br>
				<input type="submit" onclick="$(this).prop('disabled', true); $(this).attr('value', '処理中…'); submit(); "><br>
				Google Cloud Vision API の使用量を計測するため、送信ボタンを押すと @bluehood_admin にメールが送信されます。<br>
				メールの内容は「画像認識テスト」の一文のみであり、送信画像や利用者情報などは含まれません。<br>
				ご了承くださいm(__)m
			</form>
		</div>
	</body>
</html>
