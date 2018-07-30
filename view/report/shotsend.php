<meta charset = "UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
<?php
	include('/var/www/twiverse.php');
	$s = [
		'subject' => [
			'ja' => "Twiverseコミュニティ報告",
			'en' => "Twiverse Community Report",
		],
		'sent' => ['ja' => "送信しました！", 'en' => "Sent!", ],
		//'' => ['ja' => "", 'en' => "", ],
	];

	$content = $_POST['content'];

	//FIXME: Only do this if language is already Japanese:
	mb_language("Japanese");
	//...else do:
	//mb_language("English");
	mb_internal_encoding("UTF-8");
	mb_send_mail(MAIL_TO, 'Twiverseコミュニティ報告', $content, "From: ".MAIL_FROM."\nContent-Type: text/plain");

	die('送信しました！');
?>
