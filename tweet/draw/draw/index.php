<?php
	include('/var/www/twiverse.php');
	$conn = twitter_start();

	include('../../front.php');
	mysql_start();
	$res = mysql_fetch_assoc(mysql_query("select draw_width, draw_height, draw_autosave from user where screen_name='".$_SESSION['twitter']['screen_name']."'"));
	?><script>var canvas_width = <?php echo $res['draw_width']; ?>; var canvas_height = <?php echo $res['draw_height']; ?>; var is_autosave = <?php echo $res['draw_autosave']; ?>; </script><?php
	mysql_close();
?>
<!DOCTYPE html>
<html>
	<head>
		<style>
canvas{
	-webkit-touch-callout:none;
	-webkit-user-select:none;
}

	.topbar{
		position: absolute !important;
		top: 0 !important;
		left: 16px !important;
		font-size: medium !important;
		height: 1em !important;
		padding: 0.25em !important;
		border-radius: 0.5em !important;
	}

#toolbox img{
	width: 24px;
}

	/*.sidemenu{
		display: none;
	}
        .main{
                padding-left: 1em !important;
        }*/
		</style>
	</head>
	<?php head(); ?>
	<head>
		<meta name="viewport" content="width=854px, initial-scale=1.0, user-scalable=no, minimal-ui">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
	</head>
	<body>
		<h2 id="title" class="topbar">お絵かきの投稿</h2>
		<div class="main paddingleft paddingright" style="min-height: 0; ">
			<center>
			<div id="toolbox" style="text-align: left; display: inline-block; ">
				　　　　<button id="fullscreen">フルスクリーン</button><br>
				<button id="save-draft">お絵かきを下書き保存</button>
				<span id="reply"></span>
				<form id="imgform" action="../../thumbup.php" method="post" enctype="multipart/form-data" target="imgform_send" style="height: 16px; ">
					<input id="selimg" name="selimg" type="file" accept="image/jpeg">
				</form>
				<iframe name="imgform_send" style="width:0px;height:0px;border:0px;"></iframe>

				<button id="clear"><img src="new.png" alt="clear"></button>
				<button id="prev"><img src="undo.png" alt="preview"></button>
				<button id="stamp"><img src="stamp.png"></button>
　
				<button id="pen_S"><img src="pencil_s.png" alt="draw_S"></button>
				<button id="pen_M"><img src="pencil_m.png" alt="draw_M"></button>
				<button id="pen_L"><img src="pencil.png" alt="draw_L"></button>
				<button id="eraser_S"><img src="eraser_s.png" alt="eraser_S"></button>
				<button id="eraser_M"><img src="eraser_m.png" alt="eraser_M"></button>
				<button id="eraser_L"><img src="eraser.png" alt="eraser_L"></button>
			</div>
			<img id="thumb" height="96px" src="../../noimage.jpg">

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

		<div id="stamp-dialog" title="スタンプの選択" style="text-align: center; ">
			<?php
				try{
					mysql_start();
					$res = mysql_query("select image_url from selstamp where screen_name = '".$_SESSION['twitter']['screen_name']."'");
					mysql_throw();
					$i = 0;
					if (mysql_num_rows($res) == 0){
                                        	?>手持ちのスタンプはありません。ツールボックスから追加してみよう！<?php
                                        }else while($image_url = mysql_fetch_assoc($res)['image_url']){
						?>
							<div style="display: inline-block; vertical-align: top; ">
								<input type="radio" name="stamp" value="<?php echo $i; ?>">
								<div id="stamp-<?php echo $i; ?>" src="<?php echo $image_url; ?>">
									<img src="<?php echo $image_url; ?>">
								</div>
							</div>
						<?php
						$i++;
					}
					mysql_close();
				}catch(Exception $e){
					catch_default($e);
				}
			?>
			<br>大きさ<input id="zoom" type="number" value="1" min="1" max="8">
		</div>
		<script>$(function(){
			$('#stamp-dialog').dialog({
				autoOpen: false,
                	        modal: true,
                	        resizable: false,
                	        draggable: false,
                	});
		});</script>
		<script type="text/javascript" src="../../common.js"></script>
		<script type="text/javascript" src="twiverse.js"></script>
	</body>
</html>
