<?php
	include('/var/www/twiverse.php');
	$s = [
		'notfound' => ['ja' => "コミュニティが存在しません。", 'en' => "The community was not found. ", ],
		'requirement' => [
			'ja' => "コミュニティに投稿するには、スクリーンショットを添付する必要があります。",
			'en' => "To post this community, please attach screenshot. ",
		],
		//'' => ['ja' => "", 'en' => ""],
	];

        echo '<script type="text/javascript"> var comm_id = undefined; var comm_name = undefined; var thumb_data = undefined; </script>';
	if (isset($_GET['comm_id'])){
                mysql_start();
                $res = mysql_fetch_assoc(mysql_query("select name, list_id from comm where id = '".$_GET['comm_id']."'"));
                if (empty($res)) die(s($s['notfound']));
                $comm_name = $res['name'];
                mysql_close();

		$twitter_admin = twitter_admin();
		if (isset($twitter_admin->get('lists/members/show', ['list_id' => $res['list_id'], 'screen_name' => $_SESSION['twitter']['account']['user']->screen_name])->id)) echo '<script type="text/javascript"> var comm_id = "'.$_GET['comm_id'].'";var comm_name = "'.$comm_name.'"; </script>';
		else echo '<script type="text/javascript"> alert("'.s($s['requirement']).'"); </script>';
        }

        echo '<script>var draft_draw = undefined; </script>';
	mysql_start();
        $res = mysql_fetch_assoc(mysql_query("select draft_draw from user where screen_name = '".$_SESSION['twitter']['screen_name']."'"));
        if (!empty($res['draft_draw'])) echo '<script>draft_draw = "'.$res['draft_draw'].'"; </script>';
        mysql_close();

	if (isset($_GET['album'])){
		$twitter = twitter_start();
		$status = $twitter->get('statuses/show', ['id' => $_GET['album']]);
		$texts = explode(' ', str_replace('@home ', '', $status->text));
		if (hash('crc32b', $texts[0]) != $texts[1]) die('ソフト情報を認識できないため、このアルバムは使用できません。');
		$soft_id = $texts[0];
		if (strtotime($status->created_at) < 1516037892) $soft_id = 'WU'.$soft_id;
		$image = base64_encode(file_get_contents($status->entities->media[0]->media_url_https));

                mysql_start();
                $res = mysql_fetch_assoc(mysql_query("select name, id from comm where soft_id = '".$soft_id."'"));
                if ($res['name']){      /* ゲームが登録済み */
                        $comm_id = $res['id'];
                        $comm_name = $res['name'];
                }else{  /* ゲームが未登録 */
			die('このゲームはコミュニティが登録されていません。');
                }
                mysql_close();
	}
        if (isset($comm_id)) echo '<script>comm_id = "'.$comm_id.'"; </script>';
        if (isset($comm_name)) echo '<script>comm_name = "'.$comm_name.'"; </script>';
        if (isset($image)) echo '<script>thumb_data = "'.$image.'"; </script>';
?>

