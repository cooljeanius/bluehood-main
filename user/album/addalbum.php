<?php
	include('/var/www/twiverse.php');

	try{
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
	$status = $conn->post('statuses/update', ['status' => '@home '.$soft_id.' '.hash('crc32b', $soft_id).' BlueHood アルバムの投稿', 'media_ids' => $thumb->media_id_string]);

	if (!isset($_SESSION['twitter']['account']['album_id'])){
		$collection = $conn->post('collections/create', ['name' => 'Twiverse_album']);
		$_SESSION['twitter']['account']['album_id'] = $collection->response->timeline_id;
	}
	$conn->post('collections/entries/add', ['id' => $_SESSION['twitter']['account']['album_id'], 'tweet_id' => $status->id_str]);

	header('location: '.DOMAIN.ROOT_URL.'user/album/');
	}catch(Exception $e){
		catch_default($e);
	}
?>

