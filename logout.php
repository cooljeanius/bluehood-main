<?php
	include('/var/www/twiverse.php');

	$_SESSION = [];
	if (isset($_COOKIE[session_name()])) setcookie(session_name(), '', time()-42000, '/');
	session_destroy();
	header( 'location: '. DOMAIN.ROOT_URL);
?>
