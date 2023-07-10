<?php include('/var/www/twiverse.php');
	$s = [
		'title' => ['ja' => "画像認識テスト", 'en' => "Image recognition test", ],
		'desc' => [
			'ja' => "Google Cloud Vision API を利用して、画像から #ハッシュタグ を自動生成するテストページです。",
			'en' => "A test page that automatically generates #hashtags from images using the Google Cloud Vision API.",
		],
		'l2' => [
			'ja' => "Google Cloud Vision API の使用量を計測するため、送信ボタンを押すと @bluehood_admin にメールが送信されます。",
			'en' => "In order to measure the usage of the Google Cloud Vision API, an email will be sent to @bluehood_admin every time the submit button is pressed.",
		],
		'l3' => [
			'ja' => "メールの内容は「画像認識テスト」の一文のみであり、送信画像や利用者情報などは含まれません。",
			'en' => "The content of the email is only one sentence saying \"Image recognition test\", and it does not include transmitted images, user information, etc.",
		],
		'Iwataism' => [
			'ja' => "ご了承くださいm(__)m", 'en' => "Please understand m(__)m",
		],
		'waitmsg' => [ 'ja' => "処理中…", 'en' => "Processing…", ],
		//'' => ['ja' => "", 'en' => "", ],
	];
	twitter_start();
?>

<!DOCTYPE html>
<html lang = "ja">
	<?php head(); ?>
	<body>
		<h2 class="topbar"><?php l($s['title']); ?></h2>
		<div class="main paddingleft paddingright">
			<a href="https://cloud.google.com/vision/">Google Cloud Vision API</a> を利用して、画像から <a href="https://twitter.com/search?q=%23%E3%83%8F%E3%83%83%E3%82%B7%E3%83%A5%E3%82%BF%E3%82%B0">#ハッシュタグ</a> を自動生成するテストページです。<br>
			<br>
			<?php l($s['l2']); ?><br>
			<?php l($s['l3']); ?><br>
			<?php l($s['Iwataism']); ?><br>
			<br>
			<form action="aitest.php" method="post" enctype="multipart/form-data">
				<input name="selimg" type="file" accept="image/jpeg"><br>
				<input type="submit" onclick="$(this).prop('disabled', true); $(this).attr('value', '処理中…'); submit(); ">
			</form>
		</div>
	</body>
</html>
