<?php
	include('/var/www/twiverse.php');

	mysql_start();	/* 旧sessionのためにscreen_nameとcolumns at 2018/01/13 */
        $res = mysql_fetch_assoc(mysql_query("select screen_name from user where screen_name = '".$_SESSION['twitter']['account']['user']->screen_name."'"));
        if (!$res['screen_name']) mysql_query("insert into user (screen_name) values ('".$_SESSION['twitter']['account']['user']->screen_name."')");

	if ($_POST['draw']){
		mysql_query("update user set draft_draw = '".$_POST['draw']."' where screen_name='".$_SESSION['twitter']['account']['user']->screen_name."'");
	}
	mysql_close();
?>
