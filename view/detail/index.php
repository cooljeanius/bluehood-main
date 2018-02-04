<?php
	include('/var/www/twiverse.php');
	$conn = twitter_reader();

	if (!isset($_GET['query'])) die('検索条件が指定されていません。');

	function printTweet($conn, $query, $sort, $n){
		$search = $conn->get('search/tweets', ['q' => $query, 'result_type' => $sort, 'count' => (string)$n])->statuses;
		echo '<div style="">';
			//foreach($search as $status) printStatus($status);
			if (empty($search)){
				echo 'ツイートがありません。';
			}else{
				foreach($search as $status){
					echo '<div style="display: inline-block; margin: 4px;">';
						echo $conn->get('statuses/oembed', ['id' => $status->id_str, 'maxwidth' => '360'])->html;
					echo '</div>';
				}
			}
		echo '</div>';
	}
?>
<!DOCTYPE html>
<html lang = "ja">
	<head>
		<meta charset = "UTF-8">
		<link rel = "stylesheet" type = "text/css" href = "<?php echo ROOT_URL; ?>main.css">
	</head>
	<body>
		<div class="topbar">ツイートの検索</div>
		<div class="main">
			検索条件: <?php echo $_GET['query']; ?><br>
			※現在、過去100件までの検索に対応しています。<br>
			<?php printTweet($conn, $_GET['query'], 'recent', 100); ?>
	</body>
</html>

