<?php
	include('/var/www/twiverse.php');

	if ($_FILES['screenshot']['name'] != ''){
		$exif = exif_read_data($_FILES['screenshot']['tmp_name']);
		if (useragent() == 'wiiu'){
			$soft_id = 'WU'.substr($_FILES['screenshot']['name'], -9, 5);
		}else if ($exif['Model'] == 'Nintendo 3DS') do{
			if (strlen($exif['Software']) != 5) die('申し訳ありませんが、この画像には対応していません。<br>Wii U、3DS、PS VITAのブラウザから無加工のスクリーンショットをお試しください。');
			$soft_id = '3D'.$exif['Software'];
		}while(0); else if ($exif['Model'] == 'PlayStation(R)Vita'){
			$soft_id = 'PV'.substr(strstr($exif['MakerNote'], 'GPNM'), 5, 9);
		}else die('申し訳ありませんが、この画像には対応していません。<br>Wii U、3DS、PS VITAのブラウザから無加工のスクリーンショットをお試しください。');
        }else die('スクリーンショットが選択されていません。');

        $conn = twitter_start();
	$thumb = $conn->upload('media/upload', ['media' => $_FILES['screenshot']['tmp_name']]);
	$status = $conn->post('statuses/update', ['status' => '@home '.$soft_id.' '.hash('crc32b', $soft_id).' Twiverseアルバムの投稿', 'media_ids' => $thumb->media_id_string]);

	$list = $conn->get('collections/list', ['screen_name' => $_SESSION['twitter']['account']['user']->screen_name, 'count' => 200])->objects->timelines;
	unset($collection_id);
	foreach($list as $id => $collection){
		if ($collection->name == 'Twiverse_album'){
			$collection_id = $id;
			break;
		}
	}
	if (!isset($collection_id)){
		$collection = $conn->post('collections/create', ['name' => 'Twiverse_album']);
		$collection_id = $collection->response->timeline_id;
		$_SESSION['twitter']['account']['album_id'] = $collection_id;
	}
	$conn->post('collections/entries/add', ['id' => $collection_id, 'tweet_id' => $status->id_str]);

	echo 'スクリーンショットをアルバムに追加しました！';
?>

