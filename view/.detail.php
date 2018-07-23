<?php
	include('/var/www/twiverse.php');
	$s = [
		'fail' => ['ja' => "検索条件が指定されていません。", 'en' => "Search condition is not specified.", ],
		'search' => ['ja' => "ツイートの検索", 'en' => "Search tweets", ],
		'all' => ['ja' => "ぜんぶみる", 'en' => "All things", ],
		//'' => ['ja' => "", 'en' => "", ],
	];
	$conn = twitter_reader();

	if (!isset($_GET['query'])) die('検索条件が指定されていません。');
	//header('location: '.'https://twitter.com/search?'.http_build_query(['q' => $_GET['query'], 'f' => 'tweets']));

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset = "UTF-8">
	</head>
	<body>
		<?php include(ROOT_PATH.'header.php'); ?>
		<div lang="ja" class="topbar">ツイートの検索</div>
		<div lang="en" class="topbar">Search Tweets</div>
		<div class="main">
			<div style="text-align: center; ">
			<?php printTweet($conn, $_GET['query'], 'recent', 100, 'false'); ?>
			<input type="button" onclick="window.open('https://twitter.com/search?<?php echo http_build_query(['q' => $_GET['query']]); ?>'); " value="ぜんぶみる">
			</div>
	</body>
</html>

