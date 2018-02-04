<?php
	include('/var/www/twiverse.php');

	$twitter_admin = twitter_admin();
	$twitter_admin->post('lists/members/create', ['list_id' => $_POST['id'], 'screen_name' => $_POST['screen_name']]);

	mysql_start();
	mysql_query("update comm set list_n=list_n+1 where list_id='".$_POST['id']."'");
	mysql_close();
?>
