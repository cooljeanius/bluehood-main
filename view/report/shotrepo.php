<meta charset = "UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<?php
	include('/var/www/twiverse.php');

	$content = '';
	if ($_FILES['selimg']['name'] != ''){
		$content .= 'ユーザーエージェント: '.$_SERVER['HTTP_USER_AGENT']."\n";
		$content .= 'ファイル名: '.$_FILES['selimg']['name']."\n";
		$exif = exif_read_data($_FILES['selimg']['tmp_name']);
		$content .= "識別情報\n".print_r($exif, true)."\n";
		if (isset($exif['MakerNote'])) $content .= "MakerNote hex: \n".bin2hex($exif['MakerNote'])."\n";
	}else{
		die('画像を添付してください。');
	}
	if ($_POST['name'] === '') die('ソフト名を入力してください。');
	$content .= 'ソフト名: '.$_POST['name']."\n";
	$content .= '連絡先: '.$_POST['contact']."\n";
?>
以下の情報を@Twiverse_admin に送信してもよろしいですか？<br>
<br>
<form action="shotsend.php" method="post">
	<textarea name="content" rows="10" cols="50" readonly><?php echo $content; ?></textarea><br>
	<input type="submit" onclick="$(this).prop('disabled', true); $(this).attr('value', '送信中…'); submit(); ">
</form>

