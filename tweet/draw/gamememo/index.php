<?php
	include('/var/www/twiverse.php');
	$conn = twitter_start();

	unset($_SESSION['gamememo']);
        unset($_SESSION['draw']);

	include('../../front.php');
?>
<!DOCTYPE html>
<html>
	<?php head(); ?>
	<head>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
	</head>
	<body>
		<h2 id="title" class="topbar">お絵かきの投稿</h2>
		<div class="main">
			<div class="paddingleft paddingright">
				<div id="reply"></div>
				<span style="font-size: small; ">スクリーンショット</span>
				<?php if (useragent() == '3ds'){ ?>
					<form id="imgform" action="../../thumbup.php" method="post" enctype="multipart/form-data" target="imgform_send">
						<input id="selimg" name="selimg" type="file" accept="image/jpeg">
					</form>
				<?php }else{ ?>
					<button onClick="$('#sc-dialog').dialog('open'); ">スクリーンショットを選択</button><br>
				<?php } ?>
				<center><img id="thumb" width="192px" src="../../noimage.jpg"></center>
				<form id="gamememo-form" action="memoup.php" method="post" enctype="multipart/form-data" target="gamememo-send">
					<span style="font-size: small; ">お絵かき ※権利等を確認し選択してください。</span>
					<input id="gamememo" name="gamememo" type="file" accept="image/jpeg"><br>
				</form>
			</div>
			<img id="draw-preview">
			<div class="paddingleft paddingright">
				<form id="filter-form" action="filter.php" method="post" enctype="multipart/form-data" target="filter-send" style="font-size: small; display: none; ">
					<input id="filter-enable" name="enable" type="checkbox">ミバえフィルターを使う<br>
					<table id="filter-setting" style="width: 100%; display: none; "><tr>
					<td style="background-color: #ffc0c0; ">
					<input name="red" value="0" type="radio"><img src="tone_0.png">
					<input name="red" value="1" type="radio" ><img src="tone_1.png">
					<input name="red" value="2" type="radio"><img src="tone_2.png"><br>
					<input name="red" value="3" type="radio"><img src="tone_3.png">
					<input name="red" value="4" type="radio"><img src="tone_4.png">
					<input name="red" value="5" type="radio"><img src="tone_5.png"><br>
					<input name="red" value="6" type="radio"><img src="tone_6.png">
					<input name="red" value="7" type="radio" checked><img src="tone_7.png">
					<input name="red" value="8" type="radio"><img src="tone_8.png"><br>
					<input name="red" value="9" type="radio"><img src="tone_9.png">
					<input name="red" value="10" type="radio"><img src="tone_10.png">
					<input name="red" value="11" type="radio"><img src="tone_11.png">
					</td>
					<td style="background-color: #c0c0ff; ">
					<input name="blue" value="0" type="radio"><img src="tone_0.png">
					<input name="blue" value="1" type="radio" checked><img src="tone_1.png">
					<input name="blue" value="2" type="radio"><img src="tone_2.png"><br>
					<input name="blue" value="3" type="radio"><img src="tone_3.png">
					<input name="blue" value="4" type="radio"><img src="tone_4.png">
					<input name="blue" value="5" type="radio"><img src="tone_5.png"><br>
					<input name="blue" value="6" type="radio"><img src="tone_6.png">
					<input name="blue" value="7" type="radio"><img src="tone_7.png">
					<input name="blue" value="8" type="radio"><img src="tone_8.png"><br>
					<input name="blue" value="9" type="radio"><img src="tone_9.png">
					<input name="blue" value="10" type="radio"><img src="tone_10.png">
					<input name="blue" value="11" type="radio"><img src="tone_11.png">
					</td>
					</tr></table>
				</form>
				<form id="sendform" action="send.php" method="post" enctype="multipart/form-data">
					<input type="text" name="dummy" style="position:absolute;visibility:hidden">
					<input id="text" name="comment" type="text" maxlength="100" placeholder="100文字までのコメントを追加できます。" style="width: 100%; "><br>
					<!--<input name="hide" type="checkbox">ネタバレ-->
					<input id="send" type="button" value="ツイート"><br>
				</form>
			</div>
			<iframe name="imgform_send" style="width:0px;height:0px;border:0px;"></iframe>
			<iframe id="gamememo-send" name="gamememo-send" style="width:0px;height:0px;border:0px;"></iframe>
			<iframe id="filter-send" name="filter-send" style="width:0px;height:0px;border:0px;"></iframe>
		</div>
		<script type="text/javascript" src="../../common.js"></script>
		<script type="text/javascript" src="draw.js"></script>
	</body>
</html>
