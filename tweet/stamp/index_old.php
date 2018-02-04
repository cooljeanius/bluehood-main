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

		<style type="text/css">
			#preview, #confirm-preview{
				border: 1px solid lightgray;
				background-color: whitesmoke;
			}
			#stamp-edit{
				//max-width: 320px;
				//max-height: 224px;
				//overflow: scroll;
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
	</head>
	<?php head(); ?>
	<body>
		<div style="position: fixed; top: -204px; left: 0px; width: 100%; text-align: center; ">
			<canvas id="preview">
			<canvas id="confirm-preview">
		</div>
		<button id="color-black" style="position: fixed; top: 32px; left: 0px; "><div style="width: 1em; height: 1em; border: 1px solid lightgray; border-radius: 50%; background-color: black; "></div></button>
		<button id="color-white" style="position: fixed; top: 32px; left: 32px; "><div style="width: 1em; height: 1em; border: 1px solid lightgray; border-radius: 50%; background-color: white; "></div></button>
		<button id="color-trans" style="position: fixed; top: 32px; left: 64px; "><div style="width: 1em; height: 1em; border: 1px solid lightgray; border-radius: 50%; background-color: lightcyan; "></div></button>
		<button id="confirm" style="position: fixed; top: 32px; left: 96px; ">ツイート</button>
		<div class="main" style="margin-top: 304px; ">
			<table id="stamp-edit"></table>
		</div>
		<form id="stampform" action="upstamp.php" method="post">
			<input id="stampform-stamp" name="stamp" type="hidden">
		</form>
	</body>
	<script type="text/javascript">
		$(function(){
			alert('投稿したスタンプは、誰でも自由に使用できることをご了承ください。\nスタンプの権利等については責任を負いかねます。');

			var width = 64, height = 64, zoom = 3;
			var color = 'black';

			var canvas = document.getElementById('preview');
			$('#preview').attr('width', width*zoom);
			$('#preview').attr('height', height*zoom);
			var ctx = canvas.getContext('2d');

			for(var i = 0; i < height; i++){
				var row = function(){
					var html = '';
					for(var j = 0; j < width; j++){
						html += '<td class="stamp-pixel" x="'+j+'" y="'+i+'"></td>';
					}
					return html;
				};

				$('#stamp-edit').append('<tr>'+row()+'</tr>');
			}

			$('#color-black').click(function(){
				color = 'black';
			});
			$('#color-white').click(function(){
				color = 'white';
			});
			$('#color-trans').click(function(){
				color = 'rgba(0, 0, 0, 0.0)';
			});

			$('.stamp-pixel').click(function(){
				var x = 0, y = 0;

				$(this).css('background-color', color);

				/*$('.stamp-pixel').each(function(){
					ctx.fillStyle = $(this).css('background-color');
					ctx.fillRect(x*zoom, y*zoom, zoom, zoom);
					if (++x >= width){
						x = 0;
						y++;
					}
				});*/

				if (color != 'rgba(0, 0, 0, 0.0)'){
					ctx.fillStyle = color;
					ctx.fillRect($(this).attr('x')*zoom, $(this).attr('y')*zoom, zoom, zoom);
				}else{
					ctx.clearRect($(this).attr('x')*zoom, $(this).attr('y')*zoom, zoom, zoom);
				}
			});

			$('#confirm').click(function(){
				/*var beginX = width, beginY = height;
				var endX = 0, endY = 0;

				$('.stamp-pixel').each(function(){
					if ($(this).css('background-color') != 'rgba(0, 0, 0, 0)'){
						var x = Number($(this).attr('x')), y = Number($(this).attr('y'));

						if (beginX > x) beginX = x;
						if (endX < x + 1) endX = x + 1;
						if (beginY > y) beginY = y;
						if (endY < y + 1) endY = y + 1;
					}
				});*/
				$('#stampform-stamp').val($('#preview')[0].toDataURL().slice(22));
				$('#stampform').submit();
			});

			setInterval(function(){
				if ($(window).scrollTop() < 240){
					$(window).scrollTop(240);
				}else if ($(window).scrollTop() > $(document).height() - 216){
					$(window).scrollTop($(document).height() - 216);
				}
			}, 16);
		});
	</script>
</html>
