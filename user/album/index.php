<?php
	include('/var/www/twiverse.php');
	//unset($_SESSION['collection_cursor']);
	twitter_start();
?>

<!DOCTYPE html>
<html>
	<head>
		<meta name="twitter:card" content="summary" />
                <meta name="twitter:site" content="@Twiverse_admin" />
                <meta name="twitter:title" content="Twiverse" />
		<meta name="twitter:description" content="Twitterを活用したUniversalゲームコミュニティ" />
                <meta name="twitter:image" content="<?php echo DOMAIN.ROOT_URL; ?>twiverse.png" />
	</head>
	<?php head(); ?>
	<body>
		<?php include(ROOT_PATH.'header.php'); ?>
		<h2 class="topbar">アルバム　β版</h2>
		<div class="main">
			<form id="sendform" action="addalbum.php" method="post" enctype="multipart/form-data" class="marginleft">
				<input name="screenshot" type="file" accept="image/jpeg"><br>
				<input type="submit" value="追加" onclick="$(this).val('送信中…'); $(this).prop('disabled', true); submit(); "><br>
				このページをお気に入りやブックマークに登録しておくと便利です。<br>
				アルバムの写真は@homeツイートとして公開されます。<br>
				初回登録時、あなたのTwitterアカウントに「Twiverse_album」コレクションを作成します。
                        </form>
		<center><?php
        		if (isset($_SESSION['twitter']['account']['album_id'])){
				$conn = twitter_start();
				$search = $conn->get('collections/entries', ['id' => $_SESSION['twitter']['account']['album_id'], 'count' => '200'])->objects->tweets;
        			if (empty($search)){
                			echo 'アルバムがありません。';
        			}else{
                			foreach($search as $status){
						//var_dump($status);
						$media = $status->entities->media[0];
						echo '<a href="action.php?'.http_build_query(['id' => $status->id_str, 'img' => $media->media_url_https]).'"><img src="'.$media->media_url_https.':small" alt="'.$media->display_url.'" style="width: 240px; border: 1px solid lightgray; border-radius: 0.5em; "></a>';
					}
				}
        		}else echo 'アルバムがありません。';
		?></center>
		</div>
	</body>
</html>
