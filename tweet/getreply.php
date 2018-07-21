<?php
	include('/var/www/twiverse.php');
	$s = [
		//'' => ['ja' => "", 'en' => "", ],
	];
	$twitter = twitter_start();
	$status = $twitter->get('statuses/show', [id => $_POST['id']]);
	die(json_encode(['screen_name' => $status->user->screen_name, 'status' => $status]));
?>
