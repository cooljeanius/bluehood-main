$('#gamememo').change(function(){
	$('#gamememo-form').submit();

	if ($(this).val() != ''){
		$('#filter-enable').prop('checked', false);
		$('#filter-form').show();
	}else{
		$('#filter-form').hide();
	}
	$('#filter-setting').hide();

	//$('#draw-preview').attr('src', tweet_url+'loader.gif');
	$('#gamememo-form *').prop('disabled', true);
	$('#filter-form *').prop('disabled', true);
	$('#sendform *').prop('disabled', true);
});

$('#filter-form').change(function(){
	$('#filter-form').submit();
	//$('#draw-preview').attr('src', tweet_url+'loader.gif');
	$('#gamememo-form *').prop('disabled', true);
	$('#filter-form *').prop('disabled', true);
	$('#sendform *').prop('disabled', true);
});

$('#gamememo-send, #filter-send').load(function(){
	var contents = $(this).contents();
	$('#draw-preview').attr('src', 'data:image/png;base64,'+contents.find('#draw').html());

	$('#gamememo-form *').prop('disabled', false);
	$('#filter-form *').prop('disabled', false);
	$('#sendform *').prop('disabled', false);
});

$('#filter-enable').change(function(){
	if ($(this).prop('checked')){
		$('#filter-setting').show();
	}else{
		$('#filter-setting').hide();
	}
});

var sendform = document.getElementById('sendform');
var send = document.getElementById('send');
send.onclick = function(){
        var confirm_comm = '';
        if (comm_id !== undefined) confirm_comm = comm_name+' コミュニティ\n';
        var confirm_text = '';
        if ($('#text').val() != '') confirm_text = '「'+$('#text').val()+'」';
        if (confirm('投稿してもよろしいですか？\n\n'+confirm_comm+confirm_text)){
		if (comm_id !== undefined){
			var thumb = document.createElement('input');
			thumb.setAttribute('name', 'thumb');
			thumb.setAttribute('type', 'hidden');
			thumb.setAttribute('value', thumb_data);
			sendform.appendChild(thumb);

			var id = document.createElement('input');
			id.setAttribute('name', 'comm_id');
			id.setAttribute('type', 'hidden');
			id.setAttribute('value', comm_id);
			sendform.appendChild(id);
		}

		sendform.submit();
		send.setAttribute('disabled', 'disabled');	
		send.setAttribute('value', '送信中…');
	}
};

