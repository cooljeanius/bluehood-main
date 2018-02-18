<?php
	include('/var/www/twiverse.php');

	try{
		$twitter = twitter_start();
		mysql_start();

		mysql_query("update user set post_register=".var_export(isset($_POST['post_register']), true)." where screen_name='".$_SESSION['twitter']['screen_name']."'");
		mysql_throw();

		mysql_query("update user set draw_autosave=".var_export(isset($_POST['draw_autosave']), true)." where screen_name='".$_SESSION['twitter']['screen_name']."'");
		mysql_throw();

		mysql_query("update user set draw_width=".$_POST['draw_width'].", draw_height=".$_POST['draw_height']." where screen_name='".$_SESSION['twitter']['screen_name']."'");
		mysql_throw();

		mysql_query("update user set draw_sc='".$_POST['draw_sc']."' where screen_name='".$_SESSION['twitter']['screen_name']."'");
		mysql_throw();

		$detectors = mysql_throw(mysql_query("select prefix, name from detector"));
		while($detector = mysql_fetch_assoc($detectors)){
			mysql_query("update user set en_".$detector['prefix']." = ".var_export(isset($_POST['en_'.$detector['prefix']]), true)." where screen_name='".$_SESSION['twitter']['screen_name']."'");
			mysql_throw();
			mysql_query("update user set list_".$detector['prefix']." = ".var_export(isset($_POST['list_'.$detector['prefix']]), true)." where screen_name='".$_SESSION['twitter']['screen_name']."'");
			mysql_throw();
		}

		mysql_close();

		header('location: '.DOMAIN.ROOT_URL.'user/');
	}catch(Exception $e){
		catch_default($e);
	}
?>
