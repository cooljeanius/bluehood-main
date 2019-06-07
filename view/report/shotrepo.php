<meta charset = "UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<?php
	include('/var/www/twiverse.php');
	$s = [
		'UA' => ['ja' => "ユーザーエージェント: ", 'en' => "User agent: ", ],
		'name' => ['ja' => "ファイル名: ", 'en' => "File name: ", ],
		'ID' => ['ja' => "識別情報", 'en' => "Metadata", ],
		'noimg' => ['ja' => "画像を添付してください。", 'en' => "Please attach an image.", ],
		'noname' => ['ja' => "ソフト名を入力してください。", 'en' => "Please enter the name of the game.", ],
		'newname' => ['ja' => 'ソフト名: ', 'en' => "Game name: ", ],
		'contact' => ['ja' => "連絡先: ", 'en' => "Contact info: ", ],
		'confirm' => [
			'ja' => "以下の情報を@Twiverse_admin に送信してもよろしいですか？",
			'en' => "Are you sure you want to send the following information to @Twiverse_admin?",
		],
		'sending' => ['ja' => "送信中…", 'en' => "Sending…", ],
		//'' => ['ja' => "", 'en' => "", ],
	];

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
<?php l($s['confirm']); ?><br>
<br>
<form action="shotsend.php" method="post">
	<textarea name="content" rows="10" cols="50" readonly><?php echo $content; ?></textarea><br>
	<input type="submit" onclick="$(this).prop('disabled', true); $(this).attr('value', '送信中…'); submit(); ">
</form>

