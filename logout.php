<?php
	include('/var/www/twiverse.php');

	session_destroy();
	header( 'location: '. DOMAIN.ROOT_URL);
?>
