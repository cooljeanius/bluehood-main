<?php
	include('/var/www/twiverse.php');
	//unset($_SESSION['collection_cursor']);
	twitter_start();
	$s = [
		'album' => ['ja' => "アルバム　β版", 'en' => "アルバム　β版", ],
		'addalbum' => ['ja' => "アルバムを追加する", 'en' => "Add an album", ],
		'send' => ['ja' => "送信", 'en' => "Send", ],
		'sending' => ['ja' => "送信中…", 'en' => "Sending…", ],
		'albumdesc1' => [
			'ja' => "アルバムの写真は@homeツイートとして公開されます。",
			'en' => "Photos of the album are published as @home tweets.",
		],
		'albumdesc2' => [
			'ja' => "初回登録時、あなたのTwitterアカウントに「Twiverse_album」コレクションを作成します。",
			'en' => "When you register for the first time, create \"Twiverse_album\" collection on your Twitter account.",
		],
		'noalbum' => [ 'ja' => "アルバムがありません。", 'en' => "There is no album.", ],
		'more' => ['ja' => "もっとみる", 'en' => "More"],
		//'' => ['ja' => "", 'en' => "", ],
	];
?>

<!DOCTYPE html>
<html>
	<head>
	</head>
	<?php head(); ?>
	<body>
		<?php include(ROOT_PATH.'header.php'); ?>
		<h2 class="topbar">アルバム　β版</h2>
		<div class="main">
			<div class="header">
				<form id="sendform" action="addalbum.php" method="post" enctype="multipart/form-data" class="marginleft">
					<fieldset>
					<legend>アルバムを追加する</legend>
					<input name="screenshot" type="file" accept="image/jpeg"><br>
					<input type="submit" value="送信" onclick="$(this).val('送信中…'); $(this).prop('disabled', true); submit(); "><br>
					アルバムの写真は@homeツイートとして公開されます。<br>
					初回登録時、あなたのTwitterアカウントに「Twiverse_album」コレクションを作成します。
					</fieldset>
                        	</form>
			</div>
		<br>
		<center><?php try{
        		if (isset($_SESSION['twitter']['account']['album_id'])){
				$twitter = twitter_start();
				$query = ['id' => $_SESSION['twitter']['account']['album_id'], 'count' => MAX_TWEETS];
				if (isset($_GET['i'])) $query['max_position'] = $_GET['i'];
				$collection = twitter_throw($twitter->get('collections/entries', $query));
        			if (empty($collection)){
                			echo 'アルバムがありません。';
        			}else{
				        $show_i = 0;
					?> <div> <?php
        	        			foreach($collection->response->timeline as $context){
        	        			        $status = $collection->objects->tweets->{$context->tweet->id};
							$media = $status->entities->media[0];
							echo '<a href="action.php?'.http_build_query(['id' => $status->id_str, 'img' => $media->media_url_https]).'"><img src="'.$media->media_url_https.':small" class="card" style="display: inline-block; max-width: 240px; "></a>';
							$sort_index = $context->tweet->sort_index;
							$show_i++;
        	        			}
					?> </div> <?php
        	        		if ($show_i >= MAX_TWEETS){ ?>
						<a href="?<?php echo http_build_query([i => $sort_index]); ?>"><button><?php l($s['more']); ?></button></a>
					<?php }
				}
        		}else echo 'アルバムがありません。';
		}catch(Exception $e){ catch_default($e); } ?></center>
		</div>
	</body>
</html>
