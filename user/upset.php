<?php
	include('/var/www/twiverse.php');

	try{
		if ($_POST['draw_width']<0 || $_POST['draw_width']>440) throw new Exception('キャンバスの横サイズは 0 から 440 までにしてください。');
		if ($_POST['draw_height']<0 || $_POST['draw_height']>440) throw new Exception('キャンバスの縦サイズは 0 から 440 までにしてください。');

		$twitter = twitter_start();
		mysql_start();

		mysql_query("update user set theme='".mysql_escape_string($_POST['theme'])."' where id=".$_SESSION['twitter']['id']);
		mysql_throw();
		$_SESSION['theme'] = mysql_escape_string($_POST['theme']);

		mysql_query("update user set post_register=".var_export(isset($_POST['post_register']), true)." where id=".$_SESSION['twitter']['id']);
		mysql_throw();

		mysql_query("update user set draw_autosave=".var_export(isset($_POST['draw_autosave']), true)." where id=".$_SESSION['twitter']['id']);
		mysql_throw();

		mysql_query("update user set draw_width=".mysql_escape_string($_POST['draw_width']).", draw_height=".mysql_escape_string($_POST['draw_height'])." where id=".$_SESSION['twitter']['id']);
		mysql_throw();

		mysql_query("update user set draw_sc='".mysql_escape_string($_POST['draw_sc'])."' where id=".$_SESSION['twitter']['id']);
		mysql_throw();

		$detectors = mysql_throw(mysql_query("select prefix, name from detector"));
		while($detector = mysql_fetch_assoc($detectors)){
			mysql_query("update user set en_".$detector['prefix']." = ".var_export(isset($_POST['en_'.$detector['prefix']]), true)." where id=".$_SESSION['twitter']['id']);
			mysql_throw();
			mysql_query("update user set list_".$detector['prefix']." = ".var_export(isset($_POST['list_'.$detector['prefix']]), true)." where id=".$_SESSION['twitter']['id']);
			mysql_throw();
		}

		if ($_POST['stamp_height']<2 || $_POST['stamp_height']>21) throw new Exception('スタンプの高さは 2 から 21 までにしてください。');
		mysql_query("update user set stamp_height='".mysql_escape_string($_POST['stamp_height'])."' where id=".$_SESSION['twitter']['id']);
		mysql_throw();

		mysql_close();

		header('location: '.DOMAIN.ROOT_URL.'user/');
	}catch(Exception $e){
		catch_default($e);
	}
?>
