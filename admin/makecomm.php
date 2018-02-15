<?php
	include('/var/www/twiverse.php');

	try{
		$id = make_comm($_POST['soft_id'], $_POST['name']);
		echo '作成しました。<br>コミュニティ ID: '.$id;
	}catch(Exception $e){
		catch_default($e);
	}
?>
