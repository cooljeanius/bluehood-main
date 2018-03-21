var thumb = document.getElementById('thumb');
var comm_ids = [];

window.onerror = function(msg, file, line, column, err){
        alert("エラーが発生しました。\n"+msg);
};

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
		thumb.setAttribute('src', tweet_url + 'loader.gif');
		$('#suggest').html('');

		send.setAttribute('disabled', 'disabled');
		selimg.setAttribute('disabled', 'disabled');
	};
});

var update = function(res){
	thumb.setAttribute('src', 'data:image/jpeg;base64,' + res.image);
	updateText(res.option);

	comm_ids = [];
	var title = '';
	//var notice = "この画像は\n";
	res.comms.forEach(function(comm){
		comm_ids.push(comm.id);
		title += comm.detector+'-'+comm.name+'　';
		//notice += '「'+comm.detector+'-'+comm.name+'」';
	});
	if (title == ''){
		title = '投稿';
		//notice = 'コミュニティが見つかりませんでした。\n投稿は可能です。\n';
	}else{
		//notice += "\nコミュニティに投稿されます。\n";
	}
	$('#title').html(title);
	if (res.msg){
		//notice += res.msg;
		alert(res.msg);
	}
	//alert(notice);

	send.disabled = false;
	selimg.disabled = false;
}

var imgform_send = $('iframe[name="imgform_send"]');
imgform_send.unbind().bind('load', function(){
	var res = JSON.parse(imgform_send.contents().find('body').text());
	update(res);
});

$(function(){
	$('#text').val('#bluehood ');
	if (detect) update(detect);
});

var updateText = function(option){
	$('#suggest').html('<select id="suggest-sel"><option value="">BlueHood トレンド</option></select>');
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
