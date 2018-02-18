<?php
	include('/var/www/twiverse.php');
	include('common.php');

	$detect = detect($_FILES['selimg']);
	die(json_encode($detect));
?>
