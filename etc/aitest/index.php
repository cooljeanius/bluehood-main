<?php include('/var/www/twiverse.php');
	$s = [
		//'' => ['ja' => "", 'en' => "", ],
	];
	twitter_start();
?>

<!DOCTYPE html>
<html lang = "ja">
	<?php head(); ?>
	<body>
		<h2 class="topbar">画像認識テスト</h2>
		<div class="main paddingleft paddingright">
			<a href="https://cloud.google.com/vision/">Google Cloud Vision API</a> を利用して、画像から <a href="https://twitter.com/search?q=%23%E3%83%8F%E3%83%83%E3%82%B7%E3%83%A5%E3%82%BF%E3%82%B0">#ハッシュタグ</a> を自動生成するテストページです。<br>
			<br>
			Google Cloud Vision API の使用量を計測するため、送信ボタンを押すと @bluehood_admin にメールが送信されます。<br>
			メールの内容は「画像認識テスト」の一文のみであり、送信画像や利用者情報などは含まれません。<br>
			ご了承くださいm(__)m<br>
			<br>
			<form action="aitest.php" method="post" enctype="multipart/form-data">
				<input name="selimg" type="file" accept="image/jpeg"><br>
				<input type="submit" onclick="$(this).prop('disabled', true); $(this).attr('value', '処理中…'); submit(); ">
			</form>
		</div>
	</body>
</html>
