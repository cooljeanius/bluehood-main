<?php
	include('/var/www/twiverse.php');
	include('common.php');
	$s = [
		//'' => ['ja' => "", 'en' => "", ],
	];

	$detect = detect($_FILES['selimg']);
	die(json_encode($detect));
?>
