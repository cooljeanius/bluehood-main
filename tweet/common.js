var prev_image;
var thumb = document.getElementById('thumb');

$(function(){
	$('#reply').html('\n\
		<span style="font-size: small; ">リプライ</span><input id="reply-id" type="text" placeholder="ツイートのURLorID">\n\
	');
	$('#sendform').append('<input id="reply-id-hidden" name="reply_id" type="hidden">');
	$('#reply-id').change(function(){
		$(this).val($(this).val().split('/').pop().split('?').shift());
		$('#reply-id-hidden').val($(this).val());

		$.post(tweet_url+'getreply.php', {id: $(this).val()}, function(res){
			if (res.screen_name != null){
				$('#text').val('@'+res.screen_name+' '+$('#text').val());
				$('#text').keyup();
				alert('次のツイートにリプライします。\n「@'+res.screen_name+'」を消すとリプライが無効になりますのでご注意ください。\n\n@'+res.screen_name+' さんのツイート\n「'+res.status.text+'」');
			}else{
				alert('リプライ対象ツイートが見つかりませんでした。');
			}
		}, 'json');

		
	});

	if ($('#imgform')[0]){
		var imgform = document.getElementById('imgform');
		var selimg = document.getElementById('selimg');
		selimg.onchange = function(){
			imgform.submit();
			prev_image = $('#thumb').attr('src');
			thumb.setAttribute('src', tweet_url + 'loader.gif');

			send.setAttribute('disabled', 'disabled');
			selimg.setAttribute('disabled', 'disabled');	
			$('#sc-dialog').dialog('close');
		};
	}else $.post(tweet_url+'getalbum.php', {}, function(res){
		var album_html = '<div id="album-list" style="display: none; text-align: center; ">';
		res.album.forEach(function(album){
			album_html += '<div style="display: inline-block; width: 150px; ">\n\
				<img src="'+album.img+'" style="width: 100%; " class="album-button" tweet-id="'+album.tweet_id+'">\n\
			</div>';
		});
		album_html += '</div>';

		$('.main').append('<div id="sc-dialog" title="スクリーンショットの選択" style="font-size: small; ">\n\
			<form id="imgform" action="'+tweet_url+'thumbup.php" method="post" enctype="multipart/form-data" target="imgform_send">\n\
				<input id="selimg" name="selimg" type="file" accept="image/jpeg">\n\
				<input id="selalbum" name="selalbum" type="hidden">\n\
			</form>\n\
			<a href="'+root_url+'user/album/" target="_blank">アルバム</a>から\n\
			<button onclick="$(\'#album-list\').css(\'display\', \'block\'); $(this).hide(); ">アルバムを選択</button>\n\
			'+album_html+'\n\
		</div>');

		var imgform = document.getElementById('imgform');
		var selimg = document.getElementById('selimg');
		selimg.onchange = function(){
			imgform.submit();
			prev_image = $('#thumb').attr('src');
			thumb.setAttribute('src', tweet_url + 'loader.gif');

			send.setAttribute('disabled', 'disabled');
			selimg.setAttribute('disabled', 'disabled');	
			$('#sc-dialog').dialog('close');
		};

		$('.album-button').click(function(){
			$('#selalbum').val($(this).attr('tweet-id'));
			imgform.submit();
			prev_image = $('#thumb').attr('src');
			thumb.setAttribute('src', tweet_url + 'loader.gif');

			send.setAttribute('disabled', 'disabled');
			selimg.setAttribute('disabled', 'disabled');	
			$('#sc-dialog').dialog('close');
		});

		var width = $('body').width()*0.95;
		$('#sc-dialog').dialog({
			autoOpen: false,
			width: width,
			modal: true,
			resizable: false,
			draggable: false,
			position: ['center', 'top'],
		});
	}, 'json');
});

var imgform_send = $('iframe[name="imgform_send"]');
imgform_send.unbind().bind('load', function(){
	var err = imgform_send.contents().find('#err').html();

	if (err !== undefined){	// エラー
		send.disabled = false;
		selimg.disabled = false;
		thumb.setAttribute('src', prev_image);
		alert(err);
	}else{
		var name = imgform_send.contents().find('#name').html();
		if (name === undefined){	// 画像を外す
			send.disabled = false;
			selimg.disabled = false;
			comm_id = undefined;
			comm_name = undefined;
			thumb_data = imgform_send.contents().find('#data').html();
			thumb.setAttribute('src', 'data:image/jpeg;base64,' + thumb_data);
			$('#title').html('投稿');
			updateText();
		}else{
			var id = imgform_send.contents().find('#id').html();
			if (id !== undefined){	// コミュニティ選択済み
				send.disabled = false;
				selimg.disabled = false;
				comm_id = id;
				comm_name = name
				thumb_data = imgform_send.contents().find('#data').html();
				thumb.setAttribute('src', 'data:image/jpeg;base64,' + thumb_data);
				$('#title').html(comm_name + 'の投稿');
				updateText();
				if (comm_id == 'default0') alert('この画像は「未分類 コミュニティ」に投稿されます。\n画像の権利・プライバシー等についてご確認のうえ、ツイートしてください。\n\nWii U・3DS・PS VITAのスクリーンショットは、それぞれのブラウザから投稿するとコミュニティに登録できます。');
			}else{
				var soft_id = imgform_send.contents().find('#soft_id').html();
				var default_name = name;
				var soft_name = prompt("このソフトのコミュニティはまだ設立されていません。\nソフト名を入力して、コミュニティを作成してください。\n※既に入力されている場合は、確認のうえOKボタンを押してください。", name);
				if (soft_name !== null){
					$.post(tweet_url + 'makecomm.php', {soft_id: soft_id, name: soft_name}, function(res){
						var err = res.err;
						if (err === undefined){	// 新コミュニティを作成
							send.disabled = false;
							selimg.disabled = false;
							comm_id = res.id;
							comm_name = res.name;
							thumb_data = imgform_send.contents().find('#data').html();
							thumb.setAttribute('src', 'data:image/jpeg;base64,' + thumb_data);
							$('#title').html(comm_name + 'の投稿');
							updateText();
							alert('祝！新コミュニティ設立！\n\n「'+res.name+' コミュニティ」');
						}else{
							send.disabled = false;
							selimg.disabled = false;
							thumb.setAttribute('src', prev_image);
							alert(err);
						}
					}, 'json');
				}else{	/* コミュニティ設立キャンセル */
					send.disabled = false;
					selimg.disabled = false;
					thumb.setAttribute('src', prev_image);
				}
			}
		}
	}
});

$(function(){
	$('#text').val('#Twiverse ');
	if (comm_name){
		$('#title').html(comm_name + 'の投稿');
	}
	updateText();
	if (thumb_data){
		thumb.setAttribute('src', 'data:image/jpeg;base64,'+thumb_data);
	}
});

var updateText = function(){
	$.post(tweet_url + 'getopt.php', {comm_id: comm_id}, function(res){
		/*$('#suggest').html('');
		res.option.forEach(function(option, i){
			var color = '#55acee';

			if (i < 6) color = 'deeppink';
			$('#suggest').append('<span class="option" onclick="var pos=$(\'#text\').get(0).selectionStart; var val=$(\'#text\').val(); $(\'#text\').val(val.substr(0,pos)+$(this).html()+val.substr(pos)); " style="color: '+color+'; ">' + option + '</span>');
		});*/
		$('#suggest').html('<select id="suggest-sel"><option value="">クイック入力</option></select>');
		res.option.forEach(function(option, i){
			var color = '#55acee';

			if (i < 6) color = 'deeppink';
			$('#suggest-sel').append('<option value="'+option+'" style="color: '+color+'; ">'+option+'</option>');
		});
		$('#suggest-sel').change(function(){
			var pos=$('#text').get(0).selectionStart;
			var val=$('#text').val();
			$('#text').val(val.substr(0,pos)+$(this).val()+val.substr(pos));
			$('#text').keyup();
		});
	}, 'json');
	//if (comm_name) $('#text').val('#'+comm_name+' '+$('#text').val());
};

/*console.log(window);
window.onbeforeunload = function(){
	console.log('test');
	alert('');
	return 'ページを移動・再読込すると投稿内容が失われます。';
};
console.log(window.onbeforeunload);
$(window).on('blur', function(){
	if (confirm('タブを移動すると、投稿内容が失われることがあります。\nよろしいですか？')){
		$(window).off('blur');
	}else{
		$(window).off('blur');
		$(window).on('blur', function(){
			return ;
		});
	}
});*/
