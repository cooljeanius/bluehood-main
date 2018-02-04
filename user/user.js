$(function(){
	
	$('#bannarimg').change(function(){
		$('#bannarform').submit();
	});

	$('#userstat > .clickable').click(function(){
		$('#userstat > li').css('border-bottom', 'none');
		$(this).css('border-bottom', '4px solid gold');

		//$('article').html('<img src="' + root_url + 'loader.gif" alt="読込中…">');
	});
});
