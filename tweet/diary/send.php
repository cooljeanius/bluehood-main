<?php
        include('/var/www/twiverse.php');
        include('../common.php');

	try{
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
	file_put_contents($thumb_path, base64_decode($_SESSION['post_image']));
	exec('sync');

        $conn = twitter_start();
	$thumb = $conn->upload('media/upload', ['media' => $thumb_path]);

	unlink($thumb_path);

	$tweet = [];
	$tweet['status'] = $_POST['text'];;
	$tweet['media_ids'] = $thumb->media_id_string;
	if (isset($_POST['reply_id'])) $tweet['in_reply_to_status_id'] = $_POST['reply_id'];

	$status = $conn->post('statuses/update', $tweet);
	if (isset($status->errors)) throw new Exception(print_r($status->errors, true));

	dropTweet($status, $conn, isset($_POST['hide']), $comm_id);

	if (isset($comm_id)&&isset($status->entities->media[0]->media_url_https)){
		mysql_start();
		mysql_query("update comm set banner='".$status->entities->media[0]->media_url_https."' where id='".$comm_id."'");
		mysql_close();
	}

	complete_page($comm_id);

	}catch(Exception $e){
		die('エラーが発生しました。<br>'.nl2br($e->getMessage()));
	}
?>
