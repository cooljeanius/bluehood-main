<?php
	include('/var/www/twiverse.php');
	$s = [
		'err_ua' => ['ja' => "Wii U ブラウザから送信してください。", 'en' => "Please send from Wii U Browser. ", ],
		'err_name' => ['ja' => "ソフト名を入力してください。", 'en' => "Please enter software's name. ", ],
		'err_length' => ['ja' => "ソフト名を25文字以内で入力してください。", 'en' => "Software's name is max 25 characters. ", ],
		'err' => ['ja' => "エラーが発生しました。", 'en' => "An error has occured. ", ],
		//'' => ['ja' => "", 'en' => "", ],
	];

	try{
		if (useragent() != 'wiiu') throw new Exception(s($s['err_ua']));

		$soft_id = mysql_escape_string('WU'.substr($_FILES['img']['name'], -9, 5));
		$_POST['name'] = mysql_escape_string($_POST['name']);
		$_POST['name'] = twitter_trimhash($_POST['name']);

		if ($_POST['name'] == ''){
			throw new Exception(s($s['err_name']));
		}
		if (mb_strlen($_POST['name']) > 25){
			throw new Exception(s($s['err_length']));
		}

		mysql_start();
		$res = mysql_fetch_assoc(mysql_throw(mysql_query("select id from comm where soft_id='$soft_id'")));
		mysql_close();
		if ($res['id']){
			$id = $res['id'];
		}else{
			$id = make_comm($soft_id, $_POST['name']);
		}

		header('location: '.DOMAIN.ROOT_URL.'view/?'.http_build_query(['comm_id' => $id]));
	}catch(Exception $e){
		catch_default($e);
	}
?>
