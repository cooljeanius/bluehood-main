<?php
        include('/var/www/twiverse.php');
	include('../../common.php');

	try{
	if (!isset($_SESSION['draw'])){
		die('お絵かきファイルが選択されていません。');
	}

        unset($comm_name);
        unset($comm_id);
        if (isset($_POST['comm_id'])){
                $comm_id = $_POST['comm_id'];
                mysql_start();

                $res = mysql_fetch_assoc(mysql_query("select name from comm where id = '".$comm_id."'"));
                $comm_name = $res['name'];

                mysql_close();
        }

	$thumb_path = tempnam('/tmp', 'php').'.jpg';
	file_put_contents($thumb_path, base64_decode($_POST['thumb']));
	$draw_path = tempnam('/tmp', 'php').'.png';
	file_put_contents($draw_path, base64_decode($_SESSION['draw']));
	exec('sync');

	if (isset($_POST['thumb'])){
		$width = max(getimagesize($thumb_path)[0], getimagesize($draw_path)[0]);
	}else{
		$width = getimagesize($draw_path)[0];
	}
	exec('convert '.$thumb_path.' -resize '.$width.'x '.$thumb_path);
	exec('convert '.$draw_path.' -resize '.$width.'x '.$draw_path);
	exec('sync');
	exec('convert -append '.$thumb_path.' '.$draw_path.' '.$thumb_path);
	exec('sync');

        $conn = twitter_start();
	$thumb = $conn->upload('media/upload', ['media' => $thumb_path]);
	if (isset($thumb->error)) throw new Exception(print_r($thumb->error, true));
	//$draw = $conn->upload('media/upload', ['media' => $draw_path]);

	unlink($thumb_path);
	unlink($draw_path);

	$tweets = [];

		$tweet = [];
		$status = '';
		if (isset($_POST['comment'])) $status .= $_POST['comment'];
		$tweet['status'] = $status;
		$tweet['media_ids'] = $thumb->media_id_string;
		if (isset($_POST['reply_id'])) $tweet['in_reply_to_status_id'] = $_POST['reply_id'];
		array_push($tweets, $tweet);

	unset($reply_id);
	unset($first_status);
	foreach($tweets as $tweet){
		if (isset($reply_id)) $tweet['in_reply_to_status_id'] = $reply_id;
		$status = $conn->post('statuses/update', $tweet);
		if (isset($status->errors)) throw new Exception(print_r($status->errors, true));
		if (!isset($first_status)) $first_status = $status;
		$reply_id = $status->id_str;
	}
	dropTweet($first_status, $conn, isset($_POST['hide']), $comm_id);

	complete_page($comm_id);
	}catch(Exception $e){
                die('エラーが発生しました。<br>'.nl2br($e->getMessage()));
        }
?>
