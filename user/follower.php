<?php
	include('/var/www/twiverse.php');
	$twitter = twitter_reader();

	echo '※現在200ユーザーまで表示できます。';
	echo '<ul class="userlist">';
	userlist($twitter->get('followers/list', ['screen_name' => $_GET['screen_name'], 'count' => 200])->users);
	echo '</ul>';
?>
