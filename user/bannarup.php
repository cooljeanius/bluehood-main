<?php
	include('/var/www/twiverse.php');
	$twitter = twitter_start();

	if ($_FILES['bannarimg']['name'] != ''){
		$twitter->post('account/update_profile_banner', ['banner' => base64_encode(file_get_contents($_FILES['bannarimg']['tmp_name']))]);
		echo 'ヘッダー画像を変更しました！';
	}else{
		echo 'Twiverseではヘッダー画像を外すことはできません。';
	}

	//header( 'location: '. DOMAIN.ROOT_URL.'guide.php');
?>
