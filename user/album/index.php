<?php
	include('/var/www/twiverse.php');
	//unset($_SESSION['collection_cursor']);
	twitter_start();
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
						echo '<a href="action.php?'.http_build_query(['id' => $status->id_str, 'img' => $media->media_url_https]).'"><img src="'.$media->media_url_https.':small" class="card"></a>';
					}
				}
        		}else echo 'アルバムがありません。';
		?></center>
		</div>
	</body>
</html>
