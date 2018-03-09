<?php
	include('/var/www/twiverse.php');
	twitter_start();
?>

<!DOCTYPE html>
<html>
	<?php head(); ?>
	<head>
<style>
	.main{
		min-height: 0 !important;
	}
	#edit-stamp{
		border: 1px solid lightgray;
	        -webkit-touch-callout:none;
	        -webkit-user-select:none;
	}
</style>
<script>
var width = 24;
var height = 24;
var zoom = 12;
var preview_zoom = 2;
	$(function(){
		var edit_width = width*zoom;
		var edit_height = height*zoom;

		var edit_black = 0;
		var edit_white = 1;
		var edit_trans = 2;
		var edit_data = [];
		for(var i = 0; i < height; i++){
			var col = [];
			for(var j = 0; j < width; j++){
				col.push(edit_trans);
			}
			edit_data.push(col);
		}

		var cur_color = edit_black;

		var ctx = $('#edit-stamp')[0].getContext('2d');
		var draw = function(){
			ctx.fillStyle = 'white';
			ctx.fillRect(0, 0, edit_width, edit_height);

			edit_data.forEach(function(col, i){
				col.forEach(function(color, j){
					switch(color){
						case edit_trans:
						ctx.fillStyle = 'lightgray';
						break;
						case edit_white:
						ctx.fillStyle = 'white';
						break;
						case edit_black:
						ctx.fillStyle = 'black';
						break;
					}
					ctx.fillRect(j*zoom, i*zoom, (j + 1)*zoom, (i + 1)*zoom);
				});
			});

			ctx.strokeStyle = '#eee';
			ctx.lineWidth = 1;
			for(var i = 0; i < width; i++){
				ctx.beginPath();
				ctx.moveTo(i*zoom, 0);
				ctx.lineTo(i*zoom, edit_height);
				ctx.closePath();
				ctx.stroke();
			}
			for(var i = 0; i < height; i++){
				ctx.beginPath();
				ctx.moveTo(0, i*zoom);
				ctx.lineTo(edit_width, i*zoom);
				ctx.closePath();
				ctx.stroke();
			}
		};

		$('#edit-stamp').attr('width', edit_width);
		$('#edit-stamp').attr('height', edit_height);
		draw();

		var edit = function(x, y){
			x = Math.floor(x/zoom);
			y = Math.floor(y/zoom);

			if (x>=0 && x<width && y>=0 && y<height){
				edit_data[y][x] = cur_color;
				draw();
			}
		};
		if ('ontouchstart' in window) $('#edit-stamp').on('touchstart touchmove', function(e){
			var touch = e.originalEvent.targetTouches[0];
			var rect = $('#edit-stamp')[0].getBoundingClientRect();
			edit(touch.clientX - rect.left, touch.clientY - rect.top);
		});
		else{
			var isdraw = false;
			$('#edit-stamp').on('mousedown', function(e){
				edit(e.offsetX, e.offsetY);
				isdraw = true;
			});
			$('#edit-stamp').on('mousemove', function(e){
				if (isdraw) edit(e.offsetX, e.offsetY);
			});
			$(window).on('mouseup', function(e){
				isdraw = false;
			});
			$('#edit-stamp').click(function(e){
				edit(e.offsetX, e.offsetY);
			});
		}

		$('#preview').attr('width', width*preview_zoom);
		$('#preview').attr('height', height*preview_zoom);
		var preview_ctx = $('#preview')[0].getContext('2d');

		$('#color-black').click(function(e){
			cur_color = edit_black;
		});
		$('#color-white').click(function(e){
			cur_color = edit_white;
		});
		$('#color-trans').click(function(e){
			cur_color = edit_trans;
		});

		$('#shift-up').click(function(e){
			var tmp = edit_data[0];
			for(var i = 0; i < height - 1; i++){
				edit_data[i] = edit_data[i + 1];
			}
			edit_data[height - 1] = tmp;
			draw();
		});
		$('#shift-down').click(function(e){
			var tmp = edit_data[height - 1];
			for(var i = height - 1; i > 0; i--){
				edit_data[i] = edit_data[i - 1];
			}
			edit_data[0] = tmp;
			draw();
		});
		$('#shift-left').click(function(e){
			for(var i = 0; i < height; i++){
				edit_data[i].push(edit_data[i].shift());
			}
			draw();
		});
		$('#shift-right').click(function(e){
			for(var i = 0; i < height; i++){
				edit_data[i].unshift(edit_data[i].pop());
			}
			draw();
		});

		$('#next').click(function(e){
			var data = String(width)+','+String(height)+',';
			edit_data.forEach(function(col, i){
				col.forEach(function(color, j){
					data += String(color);

					switch(color){
						case edit_trans:
						preview_ctx.fillStyle = 'black';
						break;
						case edit_white:
						preview_ctx.fillStyle = 'white';
						break;
						case edit_black:
						preview_ctx.fillStyle = 'black';
						break;
					}
					preview_ctx.fillRect(j*preview_zoom, i*preview_zoom, (j + 1)*preview_zoom, (i + 1)*preview_zoom);
				});
			});
			$('input[name="stamp"]').val(data);

			$('#phase-0').hide();
			$('#phase-1').show();
		});
		$('#prev').click(function(e){
			$('#phase-1').hide();
			$('#phase-0').show();
		});
		$('#phase-1').hide();

		alert("注意\n作ったスタンプはツイートとして公開され、誰でも使用できます。");
	});
</script>
	</head>
	<body>
		<h2 class="topbar">スタンプの投稿</h2>
		<div class="main" style="text-align: center; ">
			<div id="phase-0">
				<div style="display: inline-block; ">
					<div>
						<button id="color-black"><div style="width: 1em; height: 1em; border: 1px solid lightgray; border-radius: 0.5em; background-color: black; "></div></button>
						<button id="color-white"><div style="width: 1em; height: 1em; border: 1px solid lightgray; border-radius: 0.5em; background-color: white; "></div></button>
						<button id="color-trans"><div style="width: 1em; height: 1em; border: 1px solid lightgray; border-radius: 0.5em; background-color: lightgray; "></div></button>
						<span class="disabled" style="font-size: small; ">灰色は透明色です。</span>
						<span style="font-size: small; ">作るコツ<?php helpbutton('(1) なるべく真ん中に作ろう！\n(2) 16x16 ぐらいの大きさがいいかも。\n(3) 最後に、白い輪郭を描こう！ (白の輪郭がないと、Twitter で正常に表示されない場合があります。)'); ?></span>
					</div>
					<canvas id="edit-stamp"></canvas><br>
					<table style="width: 100%; "><tr>
						<td>
							<button id="shift-up">↑</button>
							<button id="shift-down">↓</button>
							<button id="shift-left">←</button>
							<button id="shift-right">→</button>
							<?php helpbutton('このボタンを押すと、スタンプが指定方向に 1 ドットずれます。\nキャンバスからはみ出しそうなときに使用してください。'); ?>
						</td>
						<td><button id="next">つぎへ</button></td>
					</tr></table>
				</div>
			</div>
			<div id="phase-1" style="position: relative; ">
				<button id="prev" style="position: absolute; top: 0; left: 0; ">もどる</button>
				<canvas id="preview"></canvas><br>
				<span style="font-size: small; ">スタンプが真っ黒?<?php helpbutton('作ったスタンプが真っ黒に表示される場合は、「もどる」を押して白い輪郭をつけてみてください。'); ?></span>
				<form method="post" action="upstamp.php">
					<textarea name="text" rows="4" style="width: 80%; " placeholder="#ハッシュタグ ライセンス表記 100文字までのコメントを追加できます。" maxlength="100"></textarea><br>
					<input name="stamp" type="hidden">
					<input type="submit" value="ツイート" onclick="$(this).val('送信中…'); $(this).prop('disabled', true); submit(); ">
				</form>
			</div>
		</div>
	</body>
</html>
