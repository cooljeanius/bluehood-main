<?php
	include('/var/www/twiverse.php');

	try{
		if (getdate()['hours'] == 6){
			$name = 'BlueHood';
			$desc = "Twitter のイメージをつなげるコミュニティ。
			The community to link images on Twitter.
			https://t.co/DxLYhFbFhM
			GitHub: https://t.co/voYZ2lrpjv
			";
			$avatar = '/var/www/html/img/twiverse/default.png';
			$banner = '';
		}else if (getdate()['hours'] == 18){
			$name = 'BanWolf & グレたノコノコ';
			$desc = "すきなものはカメラ、カメン、ツタンカ～メン。
			T・ヨシザウルス・ムンチャクッパスがてんてき。
			カメオカート8デラックスでは、カロンはいつもほうたいをもちあるいてます。
			ヘイホーはカメンをかぶってるからなかまです。
			パタパタにあこがれてます。
			";
			$avatar = '/var/www/html/img/twiverse/stray_troopa.jpg';
			$banner = '/var/www/html/img/banner/stray_troopa.jpg';
		}

		if (isset($name)){
			$twitter_admin = twitter_admin();
			twitter_throw($twitter_admin->post('account/update_profile', ['name' => $name, 'description' => $desc]));
			twitter_throw($twitter_admin->post('account/update_profile_image', ['image' => base64_encode(file_get_contents($avatar))]));
			if ($banner){
				twitter_throw($twitter_admin->post('account/update_profile_banner', ['banner' => base64_encode(file_get_contents($banner))]));
			}else{
				twitter_throw($twitter_admin->post('account/remove_profile_banner', []));
			}
		}
	}catch(Exception $e){
		catch_default($e);
	}
?>
