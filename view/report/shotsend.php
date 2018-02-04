<meta charset = "UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<?php
	include('/var/www/twiverse.php');

	$content = $_POST['content'];

	mb_language("Japanese");
	mb_internal_encoding("UTF-8");
	mb_send_mail(MAIL_TO, 'Twiverseコミュニティ報告', $content, "From: ".MAIL_FROM."\nContent-Type: text/plain");

	die('送信しました！');
?>
