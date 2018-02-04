<?php
	session_start();

	if ($_FILES['selimg']['name'] != ''){
		$_SESSION['thumb'] = file_get_contents($_FILES['selimg']['tmp_name']);
		$_SESSION['soft_id'] = substr($_FILES['selimg']['name'], -9, 5);
	}else{
		$_SESSION['thumb'] = file_get_contents('noimage.jpg');
		unset($_SESSION['soft_id']);
	}
?>
<script type="text/javascript">
        history.back();
</script>
