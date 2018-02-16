<?php
	include('/var/www/twiverse.php');
	include('common.php');

	$detect = detect($_FILES['selimg']['tmp_name']);
	die(json_encode($detect));
?>
