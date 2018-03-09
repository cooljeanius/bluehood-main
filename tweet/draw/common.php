<?php
	include('/var/www/twiverse.php');
        include('../../common.php');

	function post_draw($thumb_path, $draw_path){
	$twitter = twitter_start();

	mysql_start();
	$res = mysql_query("select draw_sc from user where id=".$_SESSION['twitter']['id']); mysql_throw();
        $set = mysql_fetch_assoc($res); mysql_throw();
	mysql_close();
	if ($set['draw_sc'] == 'vertical'){
		if ($thumb_path){
			$width = max(getimagesize($thumb_path)[0], getimagesize($draw_path)[0]);
			exec('convert '.$thumb_path.' -resize '.$width.'x '.$thumb_path);
			exec('convert '.$draw_path.' -resize '.$width.'x '.$draw_path);
			exec('sync');
			exec('convert -append '.$thumb_path.' '.$draw_path.' '.$thumb_path);
			exec('sync');
		}else{
			$thumb_path = tempnam('/tmp', 'php').'.jpg';
			$width = getimagesize($draw_path)[0];
			$height = getimagesize($draw_path)[1];

			if ($height < $width*9/16){
				$height = (int)$width*9/16;
			}else{
				$width = (int)$height*16/9;
			}

			exec('convert '.$draw_path.' -background gray -gravity center -extent '.$width.'x'.$height.' '.$thumb_path);
			exec('sync');
		}

		$thumb = $twitter->upload('media/upload', ['media' => $thumb_path]); twitter_throw($thumb);
		$imgs = [$thumb];

		unlink($thumb_path);
		unlink($draw_path);
	}else if ($set['draw_sc'] == 'separate'){
		$imgs = [];
		if ($thumb_path){
			$thumb = $twitter->upload('media/upload', ['media' => $thumb_path]); twitter_throw($thumb);
			array_push($imgs, $thumb);
			unlink($thumb_path);
			$draw = $twitter->upload('media/upload', ['media' => $draw_path]); twitter_throw($draw);
			array_push($imgs, $draw);
		}else{
			$width = getimagesize($draw_path)[0];
			$height = getimagesize($draw_path)[1];

			if ($height < $width*9/16){
				$height = (int)$width*9/16;
			}else{
				$width = (int)$height*16/9;
			}
			exec('convert '.$draw_path.' -background gray -gravity center -extent '.$width.'x'.$height.' '.$draw_path);
			exec('sync');
			$draw = $twitter->upload('media/upload', ['media' => $draw_path]); twitter_throw($draw);
			array_push($imgs, $draw);
		}
		unlink($draw_path);
	}else throw new Exception('添付画像とお絵かきの処理方法が不明です。\nお手数ですが、@bluehood_admin にお問い合わせしてください。');

	$tweet = [];
	$tweet['status'] = $_POST['comment'];
	$tweet['media_ids'] = '';
	foreach($imgs as $img) $tweet['media_ids'] .= $img->media_id_string.',';
	//$tweet['media_ids'] = rtrim($tweet['media_ids'], ',');
	if (isset($_POST['reply_id'])) $tweet['in_reply_to_status_id'] = $_POST['reply_id'];

	$status = $twitter->post('statuses/update', $tweet);
	twitter_throw($status);
	$comm_ids = json_decode($_POST['comm_ids']);
	dropTweet($status, $twitter, isset($_POST['hide']), $comm_ids);

	complete_page($comm_ids);
	}
?>
