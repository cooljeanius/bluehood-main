var prevdata_max = 11;	// UNDO バッファサイズ
canvas_width *= 2;
canvas_height *= 2;

var canvas = document.getElementById('draw');

//Safari 向けに無効
/*$(window).on('touchstart.noScroll', function(e) {
	e.preventDefault();
});
$(window).on('touchmove.noScroll', function(e) {
	e.preventDefault();
});*/

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
	var color = 'black';
	var width = 2;
	var isTouch = ('ontouchstart' in window);
	var rect = canvas.getBoundingClientRect();
	var mode = 'draw';
	var zoom = 1;
	var stamp = new Image();
	stamp.crossOrigin = '';

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
			context.drawImage(stamp, 0, 0, stamp.width, stamp.height, (x<<1) - ((stamp.width*zoom)>>1), (y<<1) - ((stamp.height*zoom)>>1), stamp.width*zoom, stamp.height*zoom);
		}
	}

	function drawmove(x, y){
		fix = fixposition(x, y);
		x = fix.x;
		y = fix.y;
		if (mode == 'draw'){
			x = Math.floor((x - width)/2);
			y = Math.floor((y - width)/2);

			var dist = Math.round(Math.sqrt(Math.pow(x - beginX, 2.0) + Math.pow(y - beginY, 2.0))) + 1;
			context.fillStyle = color;
			for(var i = 0; i < dist; i++){
				var t = i/(dist - 1);
				context.fillRect((beginX + Math.round((x - beginX)*t))*2, (beginY + Math.round((y - beginY)*t))*2, 2*width, 2*width);
			}

			beginX = x;
 			beginY = y;
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
                                                if (isdraw) prevdata.push(context.getImageData(0, 0, canvas_width, canvas_height));
						if (prevdata.length > prevdata_max) prevdata.shift(); 
                                                //save_draft();
                                        }
					isdraw = false;
					beginX = -1;
				}
			}
		}, 1);
	}else{
		if (isTouch){
			canvas.ontouchstart = function(e){
				var touch = e.changedTouches[0];
				rect = canvas.getBoundingClientRect();

				drawstart(touch.clientX - rect.left, touch.clientY - rect.top);
				e.preventDefault();
			}

			canvas.ontouchmove = function(e){
				var touch = e.changedTouches[0];

				drawmove(touch.clientX - rect.left, touch.clientY - rect.top);
				e.preventDefault();
			};

			canvas.ontouchend = function(e){
				//save_draft();
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
				if (isDraw) prevdata.push(context.getImageData(0, 0, canvas_width, canvas_height));
				if (prevdata.length > prevdata_max) prevdata.shift(); 
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

	var pen_S = document.getElementById('pen_S');
	pen_S.onclick = function(){
		color = 'black';
		width = 1;
		mode = 'draw';
	};
	var pen_M = document.getElementById('pen_M');
	pen_M.onclick = function(){
		color = 'black';
		width = 2;
		mode = 'draw';
	};
	var pen_L = document.getElementById('pen_L');
	pen_L.onclick = function(){
		color = 'black';
		width = 4;
		mode = 'draw';
	};
	var eraser_S = document.getElementById('eraser_S');
	eraser_S.onclick = function(){
		color = 'white';
		width = 1;
		mode = 'draw';
	};
	var eraser_M = document.getElementById('eraser_M');
	eraser_M.onclick = function(){
		color = 'white';
		width = 2;
		mode = 'draw';
	};
	var eraser_L = document.getElementById('eraser_L');
	eraser_L.onclick = function(){
		color = 'white';
		width = 4;
		mode = 'draw';
	};

	$('#stamp').click(function(){
		var i = $('input[name=stamp]').attr('checked', false);
		$('#stamp-dialog').dialog('open');
	});
	$('input[name=stamp]').click(function(){
		var i = $('input[name=stamp]:checked').val();
		stamp.src = $('#stamp-'+i).attr('src');
		zoom = Math.round($('#zoom').val());
		$('#stamp-dialog').dialog('close');
	});
	stamp.onload = function(){
		mode = 'stamp';
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

	//var is_autosave = confirm('お絵かきを1分ごとに自動で下書き保存してもよいですか？\nサーバー通信が発生します。');
	setInterval(function(){
		if (is_autosave) save_draft();
	}, 60000);

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

