<?php
	session_start();

	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", FALSE);
	header("Pragma: no-cache");

	header('Content-Type: image/jpeg');
	if (isset($_SESSION['thumb'])){
		echo $_SESSION['thumb'];
	}else{
		readfile('noimage.jpg');
	}
?>
