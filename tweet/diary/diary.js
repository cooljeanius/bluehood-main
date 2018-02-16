$('#text').keyup(function(){
	var count = document.getElementById('count');
	var len = twttr.txt.getTweetLength($('#text').val());

	if (len <= 280){
		count.innerHTML = len + ' / 280';
	}else{
		count.innerHTML = '<font color="orange">' + len + '</font> / 280';
	}
});

/*var ua = navigator.userAgent.toLowerCase();
if (ua.indexOf('nintendo 3ds') != -1){
	$('#text').attr('cols', '40');	// 3DS
}else{
	$('#text').attr('cols', '50');	// others
	$('#text').css('font-size', 'x-large'); 
}*/

var sendform = document.getElementById('sendform');
var send = document.getElementById('send');
send.onclick = function(){
        var confirm_comm = '';
        if (comm_id !== undefined) confirm_comm = comm_name+' コミュニティ\n';
        var confirm_text = '';
        if ($('#text').val() != '') confirm_text = '「'+$('#text').val()+'」';
        if (confirm('投稿してもよろしいですか？\n\n'+confirm_comm+confirm_text)){
	        if (comm_id !== undefined){
	                /*var thumb = document.createElement('input');
	                thumb.setAttribute('name', 'thumb');
	                thumb.setAttribute('type', 'hidden');
	                thumb.setAttribute('value', thumb_data);
	                sendform.appendChild(thumb);*/

	                var id = document.createElement('input');
	                id.setAttribute('name', 'comm_id');
	                id.setAttribute('type', 'hidden');
	                id.setAttribute('value', comm_id);
	                sendform.appendChild(id);
        	}

		send.setAttribute('disabled', 'disabled');
		send.setAttribute('value', '送信中…');
		sendform.submit();
	}
};
