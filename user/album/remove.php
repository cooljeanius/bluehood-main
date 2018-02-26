<?php
	include('/var/www/twiverse.php');

	try{
		$twitter = twitter_start();
		twitter_throw($twitter->post('statuses/destroy', ['id' => $_GET['album']]));
		header('location: '.DOMAIN.ROOT_URL.'/user/album/');
	}catch(Exception $e){
		catch_default($e);
	}
?>

