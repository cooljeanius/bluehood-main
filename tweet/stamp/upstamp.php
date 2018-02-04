<?php
        include('/var/www/twiverse.php');

	if (isset($_POST['stamp'])){
		$stamp_data = explode(',', $_POST['stamp']);
		$width = (int)$stamp_data[0];
		$height = (int)$stamp_data[1];
		$data = $stamp_data[2];
		unset($stamp_data);

		$stamp = imagecreatetruecolor($width*2, $height*2);
		$color_id = [imagecolorallocate($stamp, 0, 0, 0), imagecolorallocate($stamp, 255, 255, 255), imagecolorallocate($stamp, 128, 128, 128)];
		$i = 0;
		for($y = 0; $y < $height; $y++) for($x = 0; $x < $width; $x++){
			imagesetpixel($stamp, $x*2, $y*2, $color_id[$data[$i]]);
			imagesetpixel($stamp, $x*2 + 1, $y*2, $color_id[$data[$i]]);
			imagesetpixel($stamp, $x*2, $y*2 + 1, $color_id[$data[$i]]);
			imagesetpixel($stamp, $x*2 + 1, $y*2 + 1, $color_id[$data[$i]]);
			$i++;
		}
		imagecolortransparent($stamp, $color_id[2]);

		$stamp_path = tempnam('/tmp', 'php').'.png';
		imagepng($stamp, $stamp_path);
		exec('sync');
		exec('convert '.$stamp_path.' -background none -gravity center -extent '.(($width + 2)*2).'x'.(($height + 2)*2).' '.$stamp_path);
		exec('sync');

        	$twitter = twitter_start();
		$stamp = $twitter->upload('media/upload', ['media' => $stamp_path]);

		unlink($stamp_path);

		$tweet = [];
		$tweet['status'] = $_POST['text'];
		$tweet['media_ids'] = $stamp->media_id_string;
		$status = $twitter->post('statuses/update', $tweet);

		$twitter_admin = twitter_admin();
		$twitter_admin->post('collections/entries/add', ['id' => 'custom-'.COLLECTON_STAMP, 'tweet_id' => $status->id_str]);
	}
?>
ツイートしました！
