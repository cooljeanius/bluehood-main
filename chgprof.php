<?php
	include('/var/www/twiverse.php');

	try{
		if (getdate()['hours'] == 6){
			$name = 'BlueHood';
			$avatar = '/var/www/html/img/twiverse/default.png';
		}else if (getdate()['hours'] == 18){
			$name = 'BanWolf (グレたノコノコ)';
			$avatar = '/var/www/html/img/twiverse/banwolf.png';
		}

		if (isset($name)){
			$twitter_admin = twitter_admin();
			twitter_throw($twitter_admin->post('account/update_profile', ['name' => $name]));
			twitter_throw($twitter_admin->post('account/update_profile_image', ['image' => base64_encode(file_get_contents($avatar))]));
		}
	}catch(Exception $e){
		catch_default($e);
	}
?>
