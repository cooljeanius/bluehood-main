<?php
	include('/var/www/twiverse.php');

        $conn = twitter_start();
	$collection = $conn->post('collections/create', ['name' => 'Twiverse']);
	$_SESSION['twitter']['account']['collection_id'] = $collection->response->timeline_id;
	header('Location: '.DOMAIN.ROOT_URL.'user/');
?>
