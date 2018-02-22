var profile = function(){
	$('.profile').css('min-height', $('.profile').width()/3.0);
	if ($(window).width() >= 766){
		$('.profile').css('padding-top', '');
	}else{
		$('.profile').css('padding-top', $('.profile').width()/3.0);
	}
};
$(function(){
	profile();
	$(window).resize(profile);
	$(window).load(profile);
	$('.profile').click(function(){
		$('.profile-article').toggle();
	});
});
