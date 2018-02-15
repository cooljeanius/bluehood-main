<?php
	include('/var/www/twiverse.php');

	if (isset($_COOKIE["PHPSESSID"])) setcookie("PHPSESSID", '', time() - 1800, '/');
	session_destroy();
	header( 'location: '. DOMAIN.ROOT_URL);
?>
