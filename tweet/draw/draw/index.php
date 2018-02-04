<?php
	include('/var/www/twiverse.php');
	$conn = twitter_start();

	include('../../front.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<link rel = "stylesheet" type = "text/css" href = "draw.css">
	</head>
	<?php head(); ?>
	<head>
		<meta name="viewport" content="width=854px, initial-scale=1.0, user-scalable=no">
	</head>
	<body>
		<h2 id="title" class="topbar">お絵かきの投稿　安定版</h2>
		<div class="main paddingleft paddingright" style="min-height: 0; ">
			<center>
			<div id="toolbox" style="text-align: left; display: inline-block; ">
				<br>
				<button id="save-draft">お絵かきを下書き保存</button>
				<span id="reply"></span>
				<a href="latest.php"><button>最新版へ</button></a> <span style="cursor: pointer; border-radius: 50%; background-color: white; " onclick="alert('このお絵かき投稿は安定版です。\nスタンプ機能とアルバム機能を省くことで安定化を図りました。\nこれらの機能を使用する場合は最新版を利用してください。');">？</span>
				<form id="imgform" action="../../thumbup.php" method="post" enctype="multipart/form-data" target="imgform_send" style="padding: 0; margin: 0; ">
					<input id="selimg" name="selimg" type="file" accept="image/jpeg">
				</form>
				<iframe name="imgform_send" style="width:0px;height:0px;border:0px;"></iframe>

				<button id="clear"><img src="new.png" alt="clear"></button>
				<button id="prev"><img src="undo.png" alt="preview"></button>
　
				<button id="pen_S"><img src="pencil_s.png" alt="draw_S"></button>
				<button id="pen_M"><img src="pencil_m.png" alt="draw_M"></button>
				<button id="pen_L"><img src="pencil.png" alt="draw_L"></button>
				<button id="eraser_S"><img src="eraser_s.png" alt="eraser_S"></button>
				<button id="eraser_M"><img src="eraser_m.png" alt="eraser_M"></button>
				<button id="eraser_L"><img src="eraser.png" alt="eraser_L"></button>
			</div>
			<img id="thumb" height="108px" src="../../noimage.jpg">

				<canvas id="draw" style="border: 1px solid lightgray; "></canvas>
				<form id="sendform" action="send.php" method="post" enctype="multipart/form-data">
					<input type="text" name="dummy" style="position:absolute;visibility:hidden">
					<!--<input name="hide" type="checkbox">ネタバレ-->
					<table style="width: 600px; "><tr>
						<td><input id="send" type="button" value="ツイート"></td>
						<!--<td id="suggest"></td>-->
						<td><input id="text" name="comment" type="text" maxlength="100" style="width: 100%; "></td>
					</tr></table>
				</form>
			</center>
		</div>
		<script type="text/javascript" src="../../common.js"></script>
		<script type="text/javascript" src="stable.js"></script>
	</body>
</html>
