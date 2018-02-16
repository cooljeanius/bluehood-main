<?php
	include('/var/www/twiverse.php');
	include('common.php');

	$detect = detect($_FILES['selimg']['tmp_name']);

	echo '<div id="data">'.$detect['data'].'</div>';
	echo '<div id="option">'.json_encode($detect['option']).'</div>';
	if (isset($detect['soft_id'])) echo '<div id="soft_id">'.$detect['soft_id'].'</div>';
	if (isset($detect['comm_id'])) echo '<div id="id">'.$detect['comm_id'].'</div>';
	if (isset($detect['name'])) echo '<div id="name">'.$detect['name'].'</div>';
?>
