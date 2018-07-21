<?php
	include('/var/www/twiverse.php');
	$s = [
		//'' => ['ja' => "", 'en' => "", ],
	];
	exec('mv '.$_FILES['gamememo']['tmp_name'].' '.$_FILES['gamememo']['tmp_name'].'jpg');
	exec('convert '.$_FILES['gamememo']['tmp_name'].'jpg '.$_FILES['gamememo']['tmp_name'].'png');
	exec('sync');
	$_SESSION['gamememo'] = file_get_contents($_FILES['gamememo']['tmp_name'].'png');
	$_SESSION['draw'] = base64_encode($_SESSION['gamememo']);
	unlink($_FILES['gamememo']['tmp_name'].'jpg');
	unlink($_FILES['gamememo']['tmp_name'].'png');
?>
<div id="draw"><?php echo $_SESSION['draw']; ?></div>
