<?php
	include('/var/www/twiverse.php');
	$s = [
		'name' => [ 'ja' => "グレたノコノコ", 'en' => "Gray Koopa Troopa", ],
		'desc' => [
			'ja' => "BlueHood の夜の姿。
			すきなものはカメラ、カメン、ツタンカ～メン。
			カメオカート8デラックスでは、カロンはいつもほうたいをもちあるいてます。
			ヘイホーはカメンをかぶってるからなかまです。
			パタパタにあこがれてます。
			ヨッシーがてんてき。
			",
			'en' => "BlueHood's night appearance.
			My favorite things are cameras, masks, and Tutankhamen.
			In Kameo Kart 8 Deluxe, Dry Bones always has fantastic taste.
			Because Shy Guy is wearing a mask, it is alright.
			I long for a Paratroopa.
			Yoshi is strange.
			",
		],
		//'' => ['ja' => "", 'en' => "", ],
	];

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
			$name = 'グレたノコノコ';
			$desc = "BlueHood の夜の姿。
			すきなものはカメラ、カメン、ツタンカ～メン。
			カメオカート8デラックスでは、カロンはいつもほうたいをもちあるいてます。
			ヘイホーはカメンをかぶってるからなかまです。
			パタパタにあこがれてます。
			ヨッシーがてんてき。
			";
			$avatar = '/var/www/html/img/twiverse/banwolf.png';
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
