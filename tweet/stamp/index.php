<?php
	include('/var/www/twiverse.php');
	//unset($_SESSION['collection_cursor']);
	twitter_start();
?>

<!DOCTYPE html>
<html>
	<?php head(); ?>
	<head>
		<meta name="twitter:card" content="summary" />
                <meta name="twitter:site" content="@Twiverse_admin" />
                <meta name="twitter:title" content="Twiverse" />
		<meta name="twitter:description" content="Twitterを活用したUniversalゲームコミュニティ" />
                <meta name="twitter:image" content="<?php echo DOMAIN.ROOT_URL; ?>twiverse.png" />

		<style>
			#confirm-preview{
				background: -webkit-gradient(linear, left top, right bottom,
					from(rgba(255,0,0,0.1)),
					color-stop(14%, rgba(255,69,0,0.2)),
					color-stop(28%, rgba(255,255,0,0.2)),
					color-stop(42%, rgba(0,128,0,0.2)),
					color-stop(56%, rgba(0,0,255,0.2)),
					color-stop(70%, rgba(75,0,130,0.2)),
					to(rgba(238,130,238,0.2))
				);
				border: 1px solid lightgray;
			}

			#stamp-edit{
				background: -webkit-gradient(linear, left top, right bottom,
					from(rgba(255,0,0,0.1)),
					color-stop(14%, rgba(255,69,0,0.2)),
					color-stop(28%, rgba(255,255,0,0.2)),
					color-stop(42%, rgba(0,128,0,0.2)),
					color-stop(56%, rgba(0,0,255,0.2)),
					color-stop(70%, rgba(75,0,130,0.2)),
					to(rgba(238,130,238,0.2))
				);
				border-spacing: 0;
				border-right: 1px solid lightgray;
				border-bottom: 1px solid lightgray;
			}

			.stamp-pixel{
				min-width: 0.5em;
				height: 0.5em;
				border-left: 1px solid lightgray;
				border-top: 1px solid lightgray;
			}
		</style>
	<script>
		$(document).ready(function(){
			//var width = 64, height = 64, zoom = 3;
			var window_width = 24, window_height = 24, zoom = 8;
			var color = 'rgb(0, 0, 0)';
			var mode = 'pen';

			for(var i = 0; i < window_height; i++){
				var row = function(){
					var html = '';
					for(var j = 0; j < window_width; j++){
						html += '<td class="stamp-pixel" x="'+j+'" y="'+i+'"></td>';
					}
					return html;
				};

				$('#stamp-edit').append('<tr>'+row()+'</tr>');
			}

			$('#color-black').click(function(){
				color = 'rgb(0, 0, 0)';
			});
			$('#color-white').click(function(){
				color = 'rgb(255, 255, 255)';
			});
			$('#color-trans').click(function(){
				color = 'rgba(0, 0, 0, 0)';
			});
			$('#tool-pen').click(function(){
				mode = 'pen';
			});
			$('#tool-fill').click(function(){
				mode = 'fill';
			});

			var pset = function(target){
				if (target.css('background-color') == color) return false;
				target.css('background-color', color);
				return true;
			};

			var target_color;
			var queue = [];
			setInterval(function(){
				while(queue.length > 0){
					var target = $(queue.shift());
					var x = Number(target.attr('x')), y = Number(target.attr('y'));
					if ((x >= 0)&&(x < window_width)&&(y >= 0)&&(y < window_height)&&(target.css('background-color') == target_color)){
						if (pset(target)){
							queue.push(".stamp-pixel[x='"+(x-1)+"'][y='"+y+"']");
							queue.push(".stamp-pixel[x='"+x+"'][y='"+(y-1)+"']");
							queue.push(".stamp-pixel[x='"+(x+1)+"'][y='"+y+"']");
							queue.push(".stamp-pixel[x='"+x+"'][y='"+(y+1)+"']");
							break;
						}
					}
				}
			}, 16);

			$('.stamp-pixel').click(function(){
				if (mode == 'pen'){
					pset($(this));
				}else if (mode == 'fill'){
					target_color = $(this).css('background-color');
					queue = [this];
				}
			});

			var canvas = document.getElementById('confirm-preview');
			var ctx = canvas.getContext('2d');

			$('#confirm').click(function(){
				var beginX = window_width, beginY = window_height;
				var endX = 0, endY = 0;

				//$(this).prop('disabled', true);
				//$(this).attr('value', '処理中…');

				$('.stamp-pixel').each(function(){
					if ($(this).css('background-color') != 'rgba(0, 0, 0, 0)'){
						var x = Number($(this).attr('x')), y = Number($(this).attr('y'));

						if (beginX > x) beginX = x;
						if (endX < x + 1) endX = x + 1;
						if (beginY > y) beginY = y;
						if (endY < y + 1) endY = y + 1;
					}
				});

				var stamp_width = endX - beginX, stamp_height = endY - beginY;
				var data = String(stamp_width)+','+String(stamp_height)+',';

				$('#confirm-preview').attr('width', stamp_width*zoom);
				$('#confirm-preview').attr('height', stamp_height*zoom);

				for(var y = beginY; y < endY; y++){
					for(var x = beginX; x < endX; x++){
						var color = $(".stamp-pixel[x='"+x+"'][y='"+y+"']").css('background-color');
						switch(color){
							case 'rgb(0, 0, 0)':
							data += '0';
							break;
							case 'rgb(255, 255, 255)':
							data += '1';
							break;
							case 'rgba(0, 0, 0, 0)':
							data += '2';
							break;
						}
						ctx.fillStyle = color;
						ctx.fillRect((x - beginX)*zoom, (y - beginY)*zoom, zoom, zoom);
					}
				}

				$('#stampform-stamp').val(data);
				$('#phase-0').hide();
				$('#phase-1').show();

				//$(this).prop('disabled', false);
				//$(this).attr('value', '次へ');
			});

			$('#cancel').click(function(){
				$('#phase-1').hide();
				$('#phase-0').show();
			});

			$('#tweet').click(function(){
				$('#stampform').submit();
				$(this).prop('disabled', true);
				$(this).attr('value', '処理中…');
			});

			$('#phase-1').hide();
			alert('スタンプは誰でも自由に使用できることをご了承ください。\nスタンプの権利等については責任を負いかねます。');
		});
	</script>
	</head>
	<body>
		<h2 class="topbar">スタンプの投稿</h2>
		<div id="phase-0" class="main paddingleft paddingright" style="text-align: center; ">
			<div style="display: inline-block; text-align: left; ">
				<button id="color-black"><div style="width: 1em; height: 1em; border: 1px solid lightgray; border-radius: 0.5em; background-color: black; "></div></button>
				<button id="color-white"><div style="width: 1em; height: 1em; border: 1px solid lightgray; border-radius: 0.5em; background-color: white; "></div></button>
				<button id="color-trans"><div style="width: 1em; height: 1em; border: 1px solid lightgray; border-radius: 0.5em; background-color: lightcyan; "></div></button>
　
				<button id="tool-pen">Pen</button>
				<button id="tool-fill">Fill</button>
				<table id="stamp-edit"></table>
				<button id="confirm">次へ</button>
			</div>
		</div>
		<div id="phase-1" class="main paddingleft paddingright" style="text-align: center; ">
			<canvas id="confirm-preview"></canvas><br>
			<form id="stampform" action="upstamp.php" method="post">
				<textarea name="text" rows="4" style="width: 80%; " placeholder="#ハッシュタグ ライセンス表記 100文字までのコメントを追加できます。" maxlength="100"></textarea>
				<input id="stampform-stamp" name="stamp" type="hidden">
			</form>
			<button id="cancel">戻る</button>　
			<input id="tweet" type="button" value="ツイート">
		</div>
	</body>
</html>
