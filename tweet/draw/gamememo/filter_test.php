<?php
	include('/var/www/twiverse.php');

	header('location: '.DOMAIN.ROOT_URL.'tweet/draw/gamememo/');
?>
<!DOCTYPE html>
<html>
	<?php head(); ?>
	<head>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
	</head>
	<body>
		<h2 id="title" class="topbar">3DS ゲームメモ ミバえフィルター</h2>
			<div class="main paddingleft paddingright">
				<br>
				<p>3DSのゲームメモで描いたお絵かきの見栄え(ミバえ)がよくなるかもしれないフィルターです。<br>
				お絵かきが白黒になって赤色と青色にはトーンが付き、コミック風になります。<br>
				まだ試験段階なのでTwiverseのお絵かき投稿には実装していませんが、先行してお試しください。<br>
				Twiverseに実装しだい、このページは削除する予定です。</p>
				<form id="sendform" action="filter.php" method="post" enctype="multipart/form-data">
					ゲームメモのお絵かき<input name="gamememo" type="file" accept="image/jpeg">
					<input type="submit">
				</form>
			</div>
	</body>
</html>
