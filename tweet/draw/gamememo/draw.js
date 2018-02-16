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
        if (confirm('投稿してもよろしいですか？')){
                var id = document.createElement('input');
                id.setAttribute('name', 'comm_ids');
                id.setAttribute('type', 'hidden');
                id.setAttribute('value', JSON.stringify(comm_ids));
                sendform.appendChild(id);

		sendform.submit();
		send.setAttribute('disabled', 'disabled');	
		send.setAttribute('value', '送信中…');
	}
};

