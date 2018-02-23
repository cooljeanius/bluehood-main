<?php
	include('/var/www/twiverse.php');
	$s = [
		//'' => ['ja' => "", 'en' => "", ],
	];

	try{
		$twitter = twitter_start();	// screen_name 認証のため必要

		mysql_start();

		mysql_query("delete from selstamp where screen_name='".$_SESSION['twitter']['screen_name']."' and image_url='".mysql_escape_string($_GET['image_url'])."'");
		mysql_throw();

		mysql_close();
	}catch(Exception $e){
		throw_default($e);
	}

	header('Location: '.DOMAIN.ROOT_URL.'view/stamp/');
?>

