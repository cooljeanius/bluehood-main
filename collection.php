<?php
	include('/var/www/twiverse.php');
	$s = [
		//'' => ['ja' => '', 'en' => ''],
		'notfound' => ['ja' => 'ツイートがありません。', 'en' => 'Tweets not found. '],
		'more' => ['ja' => 'もっとみる', 'en' => 'More'],
	];

	$twitter = twitter_reader();
	mysql_start();

	$request = ['id' => $_POST['id'], 'count' => '200'];
	if (isset($_POST['next_cursor'])) $request['max_position'] = $_POST['next_cursor'];
	$collection = $twitter->get('collections/entries', $request);

	$show_i = 0;
	if (empty($collection)){
		l($s['notfound']);
	}else{
		foreach($collection->response->timeline as $context){
			$status = $collection->objects->tweets->{$context->tweet->id};
			$status->user = $collection->objects->users->{$status->user->id};
			$status->sort_index = $context->tweet->sort_index;
			if (tweet($status, isset($_POST['sub_comm']), isset($_POST['user']))) if (++$show_i >= 15) break;
			$sort_index = $status->sort_index;
		}

		echo '<div style="clear: both; "></div>';
		if (useragent() != '3ds') echo '<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>';
		if ($show_i >= 15){ ?><a href="?<?php echo http_build_query([i => $sort_index]); ?>"><button><?php l($s['more']); ?></button></a><?php } ?>
	}

	mysql_close();
?>
