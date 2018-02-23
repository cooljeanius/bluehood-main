<?php
	include('/var/www/twiverse.php');
	unset($_SESSION['collection_cursor']);
	//$conn = twitter_start();
	if (isset($_GET['comm_id'])) $comm_id = mysql_escape_string($_GET['comm_id']);

	$s = [
		'community' => ['ja' => "コミュニティ", 'en' => "community", ],
		'allposts' => ['ja' => "すべての投稿", 'en' => "All posts", ],
		'twitterlist' => ['ja' => "Twitter リスト", 'en' => "Twitter List", ],
		'twittercollection' => ['ja' => "Twitter コレクション", 'en' => "Twitter Collection", ],
		'postdraw' => ['ja' => "お絵かきを投稿する", 'en' => "Draw to this community", ],
		'postdiary' => ['ja' => "つぶやきを投稿する", 'en' => "Tweet to this community", ],
		//'' => ['ja' => "", 'en' => "", ],
	];

	//if (!isset($comm_id)) header('Location: '.DOMAIN.ROOT_URL);

	if (isset($comm_id)){
		mysql_start();
		$res = mysql_fetch_assoc(mysql_query("select soft_id,name,collection_id,list_id from comm where id = '".$comm_id."'"));
		if (isset($res['name'])){
			$detector = detector($res['soft_id']);
			$comm_name = $detector['name'].' '.$res['name'].' '.s($s['community']);
			$collection_id = $res['collection_id'];
			$list_id = $res['list_id'];
		}
		mysql_close();
	}

	if (!isset($comm_name)){
		$comm_name = s($s['allposts']);
		$collection_id = ALL_POSTS;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel = "stylesheet" type = "text/css" href = "view.css">
	</head>
	<?php head(); ?>
	<body>
		<div class="topbar"><?php
			echo $comm_name;
		?></div>
		<div class="main">
			<div class="header">
				<?php if (isset($list_id)){ ?>
					<a class="linkbutton" href="list.php?<?php echo http_build_query(['comm_id' => $comm_id]); ?>"><?php l($s['twitterlist']); ?></a>
				<?php } ?>
				<a class="linkbutton" target="_blank" href="https://twitter.com/bluehood_admin/timelines/<?php echo $collection_id; ?>"><?php l($s['twittercollection']); ?></a>
			</div>

			<?php collection('custom-'.$collection_id, $_GET['i'], !isset($comm_id), true); ?>
		</div>
	</body>
</html>
