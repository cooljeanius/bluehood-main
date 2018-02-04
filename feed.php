<?php
	include('/var/www/twiverse.php');

	mysql_start();
	foreach($_SESSION['notification_update'] as $query) mysql_query($query);
	mysql_close();

	$_SESSION['notification'] = 0;
	$_SESSION['notification_update'] = [];

	header('location: '.'https://twitter.com/i/notifications');
?>
