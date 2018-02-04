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
		<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
		<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
	</head>
	<body>
		<h2 id="title" class="topbar">お絵かきの投稿</h2>
		<div class="main paddingleft paddingright" style="min-height: 0; ">
			<center>
			<div id="toolbox" style="text-align: left; display: inline-block; ">
				<button id="save-draft">お絵かきを下書き保存</button>
				<span id="reply"></span>
				<!--<form id="imgform" action="../../thumbup.php" method="post" enctype="multipart/form-data" target="imgform_send" style="width: 48px; ">
					<input id="selimg" name="selimg" type="file" accept="image/jpeg">
				</form>-->
				<button onClick="$('#sc-dialog').dialog('open'); ">スクリーンショットを選択</button><br>
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

		<div id="stamp-dialog" title="スタンプの選択" style="text-align: center; ">
			大きさ<input id="zoom" type="number" value="1" min="1" max="8"><br>
			<?php
				$collection = $conn->get('collections/entries', ['id' => 'custom-'.COLLECTON_STAMP, 'count' => '200']);
				$i = 0;
				//foreach($collection->response->timeline as $context){
				foreach($collection->response->timeline as $context){
					$status = $collection->objects->tweets->{$context->tweet->id};
					//var_dump($status);
					?>
						<div style="display: inline-block; width: 240px; ">
							<input type="radio" name="stamp" value="<?php echo $i; ?>">
							<div id="stamp-<?php echo $i; ?>" src="<?php echo $status->entities->media[0]->media_url_https; ?>"></div>
						</div>
						<script>
							$(function(){
								$('#stamp').click(function(){
									$('#stamp-<?php echo $i; ?>').empty();
									twttr.widgets.createTweet('<?php echo $status->id; ?>', document.getElementById('stamp-<?php echo $i; ?>'));
								});
							});
						</script>
					<?php
					$i++;
				}
			?>
		</div>
		<script>$(function(){
			$('#stamp-dialog').dialog({
				autoOpen: false,
                	        width: 790,
                	        modal: true,
                	        resizable: false,
                	        draggable: false,
				position: ['center', 'top'],
                	});
		});</script>
		<script type="text/javascript" src="../../common.js"></script>
		<script type="text/javascript" src="twiverse.js"></script>
	</body>
</html>
