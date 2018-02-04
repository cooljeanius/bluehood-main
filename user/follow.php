<?php
	include('/var/www/twiverse.php');
	$twitter = twitter_reader();

	echo '※現在200ユーザーまで表示できます。';
	userlist($twitter->get('friends/list', ['screen_name' => $_GET['screen_name'], 'count' => 200])->users);
	echo '</ul>';
?>
