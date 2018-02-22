<?php
	include('/var/www/twiverse.php');

	$content = $_POST['msg'];

	mb_language("Japanese");
	mb_internal_encoding("UTF-8");
	mb_send_mail(MAIL_TO, 'BlueHood エラー報告', $content, "From: ".MAIL_FROM."\nContent-Type: text/plain");

	header('location: '.DOMAIN.ROOT_URL);
?>
