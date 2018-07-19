<?php
	include('/var/www/twiverse.php');
	include('common.php');

	$s = [
		'notfound' => ['ja' => "コミュニティが存在しません。", 'en' => "The community was not found. ", ],
		'requirement' => [
			'ja' => "コミュニティに投稿するには、スクリーンショットを添付する必要があります。",
			'en' => "To post this community, please attach a screenshot. ",
		],
		//'' => ['ja' => "", 'en' => ""],
	];

	unset($_SESSION['post_image']);

        echo '<script> var detect = undefined; </script>';
	/*if (isset($_GET['comm_id'])){
                mysql_start();
                $res = mysql_fetch_assoc(mysql_query("select name, list_id from comm where id = '".$_GET['comm_id']."'"));
                if (empty($res)) die(s($s['notfound']));
                $comm_name = $res['name'];
                mysql_close();

		$twitter_admin = twitter_admin();
		if (isset($twitter_admin->get('lists/members/show', ['list_id' => $res['list_id'], 'screen_name' => $_SESSION['twitter']['account']['user']->screen_name])->id)) echo '<script type="text/javascript"> var comm_id = "'.$_GET['comm_id'].'";var comm_name = "'.$comm_name.'"; </script>';
		else echo '<script> alert("'.s($s['requirement']).'"); </script>';
        }*/

	if (isset($_GET['album'])){
		$detect = detect('', $_GET['album']);
		?><script>detect = JSON.parse('<?php echo json_encode($detect); ?>'); </script><?php
	}

	/* 下書き (draft) */
        echo '<script>var draft_draw = undefined; </script>';
	mysql_start();
        $res = mysql_fetch_assoc(mysql_query("select draft_draw from user where id=".$_SESSION['twitter']['id']));
        if (!empty($res['draft_draw'])) echo '<script>draft_draw = "'.$res['draft_draw'].'"; </script>';
        mysql_close();
?>
