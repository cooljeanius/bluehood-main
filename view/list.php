<?php
	include('/var/www/twiverse.php');
	$s = [
		'nocomm' => [
			'ja' => "コミュニティが指定されていません。",
			'en' => "No community specified.",
		],
		'nocomm1' => [
			'ja' => "コミュニティが存在しません。",
			'en' => "There is no community.",
		],
		'list' => ['ja' => "リスト", 'en' => "list", ],
		'desc' => [
			'ja' => "Twitterを活用したUniversalゲームコミュニティ",
			'en' => "A universal game community using Twitter",
		],
		//'' => ['ja' => "", 'en' => "", ],
	];
	unset($_SESSION['collection_cursor']);

	$twitter = twitter_reader();

        if (!isset($_GET['comm_id'])) die('コミュニティが指定されていません。');
        mysql_start();
        $res = mysql_fetch_assoc(mysql_query("select name, list_id from comm where id = '".mysql_escape_string($_GET['comm_id'])."'"));
        if (empty($res)) die('コミュニティが存在しません。');
        $comm_name = $res['name'];
        $list_id = $res['list_id'];
        mysql_close();
?>

<!DOCTYPE html>
<html lang = "ja">
	<head>
		<!--<meta name="twitter:card" content="summary" />
                <meta name="twitter:site" content="@Twiverse_admin" />
                <meta name="twitter:title" content="<?php echo $comm_name; ?> リスト - Twiverse" />
                <meta name="twitter:description" content="Twitterを活用したUniversalゲームコミュニティ" />
                <meta name="twitter:image" content="<?php echo DOMAIN.ROOT_URL; ?>twiverse.png" />-->
	</head>
	<?php head(); ?>
	<body>
		<h2 class="topbar"><?php echo $comm_name; ?> <?php l($s['list']); ?></h2>
		<div class="main paddingleft paddingright">
		<?php userlist($twitter->get('lists/members', ['list_id' => $list_id, 'count' => 5000])->users); ?>
	</body>
</html>
