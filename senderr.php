<?php
	include('/var/www/twiverse.php');
	$s = [
		'subject' => [
			'ja' => "BlueHood エラー報告", 'en' => "BlueHood error report",
		],
		//'' => ['ja' => "", 'en' => "", ],
	];

	$content = $_POST['msg'];

	//FIXME: Only do this if language is already Japanese:
	mb_language("Japanese");
	//...else do:
	//mb_language("English");
	mb_internal_encoding("UTF-8");
	mb_send_mail(MAIL_TO, l($s['subject']), $content, "From: ".MAIL_FROM."\nContent-Type: text/plain");

	header('location: '.DOMAIN.ROOT_URL);
?>
