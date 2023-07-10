<?php
	include('/var/www/twiverse.php');
	$s = [
		//'' => ['ja' => "", 'en' => "", ],
	];
	//unset($_SESSION['collection_cursor']);

	$album = [];

	$conn = twitter_start();
        $list = $conn->get('collections/list', ['screen_name' => $_SESSION['twitter']['account']['user']->screen_name, 'count' => 200])->objects->timelines;
        unset($collection_id);
        foreach($list as $id => $collection){
            	if ($collection->name == 'Twiverse_album'){
                        $collection_id = $id;
                        break;
                }
        }
        if (isset($collection_id)){
		$search = $conn->get('collections/entries', ['id' => $collection_id, 'count' => '200'])->objects->tweets;
                foreach($search as $status){
			$media = $status->entities->media[0];
			array_push($album, ['tweet_id' => $status->id_str, 'img' => $media->media_url_https.':thumb']);
		}
        }

	echo json_encode(['album' => $album]);
?>

