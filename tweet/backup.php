<?php
	include('/var/www/twiverse.php');
	$s = [
		//'' => ['ja' => "", 'en' => "", ],
	];

	if ($_POST['draw']){
		mysql_start();
		mysql_throw(mysql_query("update user set draft_draw = '".mysql_escape_string($_POST['draw'])."' where id=".$_SESSION['twitter']['id']));
		mysql_close();
	}
?>
