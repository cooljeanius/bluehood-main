<?php
	include('/var/www/twiverse.php');
	$s = [
		//'' => ['ja' => "", 'en' => "", ],
	];

	if (isset($_COOKIE["PHPSESSID"])) setcookie("PHPSESSID", '', time() - 1800, '/');
	session_destroy();
	header( 'location: '. DOMAIN.ROOT_URL);
?>
