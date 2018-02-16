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

	var imgform = document.getElementById('imgform');
	var selimg = document.getElementById('selimg');
	selimg.onchange = function(){
		imgform.submit();
		prev_image = $('#thumb').attr('src');
		thumb.setAttribute('src', tweet_url + 'loader.gif');
		$('#suggest').html('');

		send.setAttribute('disabled', 'disabled');
		selimg.setAttribute('disabled', 'disabled');	
	};
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
		updateText(JSON.parse(imgform_send.contents().find('#option').html()));

		var name = imgform_send.contents().find('#name').html();
		if (name === undefined){	// 画像を外す
			send.disabled = false;
			selimg.disabled = false;
			comm_id = undefined;
			comm_name = undefined;
			thumb_data = imgform_send.contents().find('#data').html();
			thumb.setAttribute('src', 'data:image/jpeg;base64,' + thumb_data);
			$('#title').html('投稿');
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
	$('#text').val('#bluehood ');
	if (comm_name){
		$('#title').html(comm_name + 'の投稿');
	}
	if (thumb_data){
		thumb.setAttribute('src', 'data:image/jpeg;base64,'+thumb_data);
	}
	if (option){
		updateText(option);
	}
});

var updateText = function(option){
	$('#suggest').html('<select id="suggest-sel"><option value="">クイック入力</option></select>');
	option.forEach(function(option, i){
		var color = '#55acee';
		$('#suggest-sel').append('<option value="'+option+'" style="color: '+color+'; ">'+option+'</option>');
	});
	$('#suggest-sel').change(function(){
		var pos=$('#text').get(0).selectionStart;
		var val=$('#text').val();
		$('#text').val(val.substr(0,pos)+$(this).val()+val.substr(pos));
		$('#text').keyup();
	});
};
