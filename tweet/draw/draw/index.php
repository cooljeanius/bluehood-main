<?php
	include('/var/www/twiverse.php');
	$conn = twitter_start();

	include('../../front.php');
	mysql_start();
	$res = mysql_fetch_assoc(mysql_query("select draw_width, draw_height, draw_autosave from user where id=".$_SESSION['twitter']['id']));
	?><script>var canvas_width = <?php echo $res['draw_width']; ?>; var canvas_height = <?php echo $res['draw_height']; ?>; var is_autosave = <?php echo $res['draw_autosave']; ?>; </script><?php
	mysql_close();

	$s = [
		'title' => ['ja' => "お絵かきの投稿", 'en' => "Post Drawing"],
		'save' => ['ja' => "お絵かきを下書き保存", 'en' => "Save drawing draftSave drawing draft"],
		'fullscreen' => ['ja' => "フルスクリーン", 'en' => "Fullscreen"],
		'tweet' => ['ja' => "ツイート", 'en' => "Tweet"],
		'stamp' => ['ja' => "スタンプの選択", 'en' => "Select stamp"],
		'stamp-nothing' => ['ja' => "手持ちのスタンプがありません。", 'en' => "Your stamps not found. "],
		//'' => ['ja' => "", 'en' => ""],
	];
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
		<h2 id="title" class="topbar"><?php l($s['title']); ?></h2>
		<div class="main paddingleft paddingright" style="min-height: 0; ">
			<center>
			<div id="toolbox" style="text-align: left; display: inline-block; ">
				　　　　<button id="fullscreen"><?php l($s['fullscreen']); ?></button><br>
				<button id="save-draft"><?php l($s['save']); ?></button>
				<span id="reply"></span>
				<form id="imgform" action="../../thumbup.php" method="post" enctype="multipart/form-data" target="imgform_send" style="height: 16px; ">
					<input id="selimg" name="selimg" type="file" accept="image/*"><!-- */ -->
				</form>
				<iframe name="imgform_send" style="width:0px;height:0px;border:0px;"></iframe>

				<button id="clear"><img src="new.png" alt="clear"></button>
				<button id="prev"><img src="undo.png" alt="preview"></button>
				<button id="stamp" class="pen-button"><img src="stamp.png"></button>
　
				<button id="pen_S" class="pen-button"><img src="pencil_s.png" alt="draw_S"></button>
				<button id="pen_M" class="pen-button"><img src="pencil_m.png" alt="draw_M"></button>
				<button id="pen_L" class="pen-button"><img src="pencil.png" alt="draw_L"></button>
				<button id="eraser_S" class="pen-button"><img src="eraser_s.png" alt="eraser_S"></button>
				<button id="eraser_M" class="pen-button"><img src="eraser_m.png" alt="eraser_M"></button>
				<button id="eraser_L" class="pen-button"><img src="eraser.png" alt="eraser_L"></button>
			</div>
			<img id="thumb" height="96px" src="../../noimage.jpg">

				<div id="draw-window" style="display: inline-block; position: relative; ">
					<canvas id="draw" style="border: 1px solid lightgray; "></canvas>
				</div>
				<form id="sendform" action="send.php" method="post" enctype="multipart/form-data">
					<input type="text" name="dummy" style="position:absolute;visibility:hidden">
					<table style="width: 600px; "><tr>
						<!--<td id="suggest"></td>-->
						<td><input id="text" name="comment" type="text" maxlength="100" style="width: 100%; "></td>
						<td><input id="send" type="button" value="<?php l($s['tweet']); ?>"></td>
					</tr></table>
				</form>
			</center>
		</div>

		<div id="stamp-dialog" title="<?php l($s['stamp']); ?>" style="text-align: center; ">
			<?php
				try{
					mysql_start();
					$res = mysql_query("select image_url from selstamp where screen_name = '".$_SESSION['twitter']['screen_name']."'");
					mysql_throw();
					$i = 0;
					if (mysql_num_rows($res) == 0){
						l($s['stamp-nothing']);
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
			<br>
			大きさ<select id="zoom">
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
			</select>
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
		<script>
var prevdata_max = 11;	// UNDO バッファサイズ
canvas_width *= 2;
canvas_height *= 2;

var canvas = document.getElementById('draw');

var ua = navigator.userAgent.toLowerCase();

$(window).load(function(){
var placeholder = '#ハッシュタグ @返信先 100文字までのコメントを追加できます。';
$('canvas').attr('width', canvas_width + 'px');
$('canvas').attr('height', canvas_height + 'px');
$('#text').attr('placeholder', placeholder);

var isfullscreen = false;
var isrotate = false;
$('#fullscreen').click(function(){
	if (isfullscreen){
		document.webkitCancelFullScreen();
	}else{
		if ($('.main')[0].webkitRequestFullScreen){
			$('.main')[0].webkitRequestFullScreen();
		}else{
			alert('ご利用の環境ではフルスクリーン表示ができません。');
		}
	}
});
document.onwebkitfullscreenchange = function(e){
	isfullscreen = !isfullscreen;
	if (isfullscreen){
		if (screen.width < screen.height){
			$('.main').css('-webkit-transform-origin', '50% 50%');
			$('.main').css('-webkit-transform', 'rotate(90deg)');
			isrotate = true;
		}
	}else{
		$('.main').css('-webkit-transform-origin', '');
		$('.main').css('-webkit-transform', '');
		isrotate = false;
	}
};

if (canvas.getContext){
	var context = canvas.getContext('2d');
	var beginX, beginY;
	var curX, curY;
	var color = 'black';
	var width = 2;
	var isTouch = ('ontouchstart' in window);
	var rect = canvas.getBoundingClientRect();
	var mode = 'draw';
	var zoom = 1;
	var stamp = new Image();
	stamp.crossOrigin = '';
	stamp.id = 'stamp-img';
	$('#draw-window')[0].appendChild(stamp);
	$('#stamp-img').css('position', 'absolute');
	$('#stamp-img').css('opacity', '0.5');
	$('#stamp-img').css('pointer-events', 'none');
	$('#stamp-img').hide();

	context.fillStyle = 'white';
	context.fillRect(0, 0, canvas_width, canvas_height);
	if (draft_draw){
		var image = new Image();
		image.src = draft_draw;
		image.onload = function(){
			context.drawImage(image, 0, 0);
			prevdata = [context.getImageData(0, 0, canvas_width, canvas_height)];
			delete image;
		}
	}

	var prevdata = [context.getImageData(0, 0, canvas_width, canvas_height)];

	var fixposition = function(x, y){
		var fix = {};
		if (isrotate){
			fix.x = y;
			fix.y = canvas_height-x;
		}else{
			fix.x = x;
			fix.y = y;
		}
		return fix;
	};

	function drawstart(x, y){
		cur_x = x;
		cur_y = y;
		fix = fixposition(x, y);
		x = fix.x;
		y = fix.y;
		x = Math.floor((x - width)/2);
		y = Math.floor((y - width)/2);
		beginX = x;
 		beginY = y;
		if (mode == 'draw'){
			context.fillStyle = color;
			context.fillRect(beginX*2, beginY*2, 2*width, 2*width);
		}else if (mode == 'stamp'){
		}
	}

	function drawmove(x, y){
		cur_x = x;
		cur_y = y;
		fix = fixposition(x, y);
		x = fix.x;
		y = fix.y;
		x = Math.floor((x - width)/2);
		y = Math.floor((y - width)/2);
		if (mode == 'draw'){

			var dist = Math.round(Math.sqrt(Math.pow(x - beginX, 2.0) + Math.pow(y - beginY, 2.0))) + 1;
			context.fillStyle = color;
			for(var i = 0; i < dist; i++){
				var t = i/(dist - 1);
				context.fillRect((beginX + Math.round((x - beginX)*t))*2, (beginY + Math.round((y - beginY)*t))*2, 2*width, 2*width);
			}

			beginX = x;
 			beginY = y;
		}else if (mode == 'stamp'){
			$('#stamp-img').css('left', (((x<<1) - ((stamp.width*zoom)>>1))/zoom)+'px');
			$('#stamp-img').css('top', (((y<<1) - ((stamp.height*zoom)>>1))/zoom)+'px');
			$('#stamp-img').show();
		}
	}

	function drawend(x, y){
		cur_x = x;
		cur_y = y;
		fix = fixposition(x, y);
		x = fix.x;
		y = fix.y;
		x = Math.floor((x - width)/2);
		y = Math.floor((y - width)/2);
		if (mode == 'draw'){
		}else if (mode == 'stamp'){
			context.drawImage(stamp, 0, 0, stamp.width, stamp.height, (x<<1) - ((stamp.width*zoom)>>1), (y<<1) - ((stamp.height*zoom)>>1), stamp.width*zoom, stamp.height*zoom);
			$('#stamp-img').hide();
		}
	}

	if (ua.indexOf('nintendo wiiu') != -1){	//Wii U
		var isdraw = false;
		setInterval(function(){
			if (!$('#stamp-dialog').is(':visible')){
				var gamepad = window.wiiu.gamepad.update();

				if (gamepad.tpTouch){
					var x, y;
					if (beginX == -1) rect = canvas.getBoundingClientRect();

					x = gamepad.contentX - rect.left;
					y = gamepad.contentY - rect.top;
					if (beginX == -1){
                                                if (x>=0 && x<canvas_width && y>=0 && y<canvas_height) isdraw = true;
                                                if (isdraw) drawstart(x, y);
                                        }else{
                                                drawmove(x, y);
                                        }
				}else{
					if (beginX != -1){
						drawend(cur_x, cur_y);
                                                if (isdraw) prevdata.push(context.getImageData(0, 0, canvas_width, canvas_height));
						if (prevdata.length > prevdata_max) prevdata.shift(); 
                                                //save_draft();
                                        }
					isdraw = false;
					beginX = -1;
				}
			}
		}, 1);
		/*canvas.ontouchstart = function(e){
			e.preventDefault();
		}
		canvas.ontouchmove = function(e){
			e.preventDefault();
		};
		canvas.ontouchend = function(e){
			e.preventDefault();
		};*/
	}else{
		if (isTouch){
			canvas.ontouchstart = function(e){
				var touch = e.targetTouches[0];
				rect = canvas.getBoundingClientRect();

				drawstart(touch.clientX - rect.left, touch.clientY - rect.top);
				e.preventDefault();
			}

			canvas.ontouchmove = function(e){
				var touch = e.targetTouches[0];

				drawmove(touch.clientX - rect.left, touch.clientY - rect.top);
				e.preventDefault();
			};

			canvas.ontouchend = function(e){
				//save_draft();
				drawend(touch.clientX - rect.left, touch.clientY - rect.top);
				prevdata.push(context.getImageData(0, 0, canvas_width, canvas_height));
				if (prevdata.length > prevdata_max) prevdata.shift(); 
				e.preventDefault();
			};
		}else{
			var isDraw = false;
			$('#draw').mousedown(function(e){
				isDraw = true;
				rect = canvas.getBoundingClientRect();

				drawstart(e.clientX - rect.left, e.clientY - rect.top);
				//e.preventDefault();
			});

			$(window).mousemove(function(e){
				if (isDraw) drawmove(e.clientX - rect.left, e.clientY - rect.top);
				//e.preventDefault();
			});

			$(window).mouseup(function(e){
				if (isDraw){
					drawend(e.clientX - rect.left, e.clientY - rect.top);
					prevdata.push(context.getImageData(0, 0, canvas_width, canvas_height));
					if (prevdata.length > prevdata_max) prevdata.shift(); 
				}
				isDraw = false;
				//save_draft();
				//e.preventDefault();
			});
		}
	}

	var clear = document.getElementById('clear');
	clear.onclick = function(){
		if (confirm('全消ししますか？')){
			context.fillStyle = 'white';
			context.fillRect(0, 0, canvas_width, canvas_height);
                        prevdata.push(context.getImageData(0, 0, canvas_width, canvas_height));
			if (prevdata.length > prevdata_max) prevdata.shift(); 
		}
	};

	var prev = document.getElementById('prev');
	prev.onclick = function(){
		if (prevdata.length >= 2){
        	        prevdata.pop();
			context.putImageData(prevdata[prevdata.length - 1], 0, 0);
		}
	};

	var toggle_penbutton = function(selected){
		$('.pen-button').css('border-color', '');
		$(selected).css('border-color', '#55acee');
	}

	var pen_S = document.getElementById('pen_S');
	pen_S.onclick = function(){
		color = 'black';
		width = 1;
		mode = 'draw';
		toggle_penbutton(this);
	};
	var pen_M = document.getElementById('pen_M');
	pen_M.onclick = function(){
		color = 'black';
		width = 2;
		mode = 'draw';
		toggle_penbutton(this);
	};
	var pen_L = document.getElementById('pen_L');
	pen_L.onclick = function(){
		color = 'black';
		width = 4;
		mode = 'draw';
		toggle_penbutton(this);
	};
	var eraser_S = document.getElementById('eraser_S');
	eraser_S.onclick = function(){
		color = 'white';
		width = 1;
		mode = 'draw';
		toggle_penbutton(this);
	};
	var eraser_M = document.getElementById('eraser_M');
	eraser_M.onclick = function(){
		color = 'white';
		width = 2;
		mode = 'draw';
		toggle_penbutton(this);
	};
	var eraser_L = document.getElementById('eraser_L');
	eraser_L.onclick = function(){
		color = 'white';
		width = 4;
		mode = 'draw';
		toggle_penbutton(this);
	};

	$('#stamp').click(function(){
		var i = $('input[name=stamp]').attr('checked', false);
		stamp.src = '';
		$('#stamp-dialog').dialog('open');
	});
	$('input[name=stamp]').click(function(){
		var i = $('input[name=stamp]:checked').val();
		zoom = Math.round($('#zoom').val());
		stamp.src = $('#stamp-'+i).attr('src');
		$('#stamp-img').css('zoom', zoom);
		$('#stamp-dialog').dialog('close');
	});
	stamp.onload = function(){
		mode = 'stamp';
		toggle_penbutton($('#stamp'));
	}

	$('#save-draft').click(function(){
		if (confirm('お絵かきをサーバーに下書き保存しますか？\nすでに保存されているお絵かきは上書きされます。')){
			var url = canvas.toDataURL();
			$.post(tweet_url+'backup.php', {draw: url}, function(res){
				alert('お絵かきを下書き保存しました。\n投稿画面を開くと下書きが自動的に読み込まれます。');
			}, 'html');
		}
	});

	/*window.onbeforeunload = function(){
		save_draft();
	};*/

	if (is_autosave){
		setInterval(function(){
			save_draft();
		}, 60000);
	}else{
		alert("お絵かきの自動下書き保存は無効に設定されています。\nユーザー設定にて有効にすることができます。");
	}

	var save_draft = function(){
		var url = canvas.toDataURL();
		$.post(tweet_url+'backup.php', {draw: url}, function(res){
		}, 'html');
	};
}
});

var sendform = document.getElementById('sendform');
var drawdata = document.createElement('input');
drawdata.setAttribute('name', 'draw');
drawdata.setAttribute('type', 'hidden');
sendform.appendChild(drawdata);
var send = document.getElementById('send');
send.onclick = function(){
	if (confirm('投稿してもよろしいですか？')){
                var id = document.createElement('input');
                id.setAttribute('name', 'comm_ids');
                id.setAttribute('type', 'hidden');
                id.setAttribute('value', JSON.stringify(comm_ids));
                sendform.appendChild(id);

		url = canvas.toDataURL().slice(22);
		drawdata.setAttribute('value', url);
		sendform.submit();
		send.setAttribute('disabled', 'disabled');	
		send.setAttribute('value', '送信中…');
	}
};
		</script>
	</body>
</html>
