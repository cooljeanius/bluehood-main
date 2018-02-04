<?php
	include('/var/www/twiverse.php');
	$conn = twitter_reader();

	$search = $conn->get('search/tweets', ['q' => $_POST['query'], 'result_type' => $_POST['sort'], 'count' => $_POST['n']])->statuses;
	if (empty($search)){
		echo 'ツイートがありません。';
	}else{
		foreach($search as $status){
			echo '<div style="display: inline-block; margin: 4px;">';
				echo $conn->get('statuses/oembed', ['id' => $status->id_str, 'maxwidth' => '240'])->html;
			echo '</div>';
		}
		echo '<br><center><a href="detail/?'.http_build_query(['query' => $_POST['query']]).'">すべてのツイートをみる</a></center>';
	}
?>
