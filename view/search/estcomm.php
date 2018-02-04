<?php
	include('/var/www/twiverse.php');

	mysql_start();
	$res = mysql_query("select * from comm");
	$comm_list = [];
	while($row = mysql_fetch_assoc($res)) array_push($comm_list, $row);
	mysql_close();
?>
<!DOCTYPE html>
<html lang = "ja">
	<head>
		<meta charset = "UTF-8">
		<link rel = "stylesheet" type = "text/css" href = "<?php echo ROOT_URL; ?>main.css">
		<link rel = "stylesheet" type = "text/css" href = "view.css">
	</head>
	<body>
		<div class="topbar">コミュニティを設立するには？</div>
		<div class="main">
			Wii Uインターネットブラウザより、スクリーンショット付きの投稿をすることでコミュニティを設立できます。<br>
			技術的な制約により、Wii U以外の端末からのコミュニティ設立はできません。<br>
		</div>
	</body>
</html>
