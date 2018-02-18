<?php
	include('/var/www/twiverse.php');
	$s = [
		'err_exif' => ['ja' => "正常な識別情報が見つかりません。3DS で撮影したスクリーンショットを送信してください。", 'en' => "Not found the right exif data. Please send screenshots made from 3DS. ", ],
		'err_name' => ['ja' => "ソフト名を入力してください。", 'en' => "Please enter software's name. ", ],
		'err_length' => ['ja' => "ソフト名を25文字以内で入力してください。", 'en' => "Software's name is max 25 characters. ", ],
		'err' => ['ja' => "エラーが発生しました。", 'en' => "An error was occured. ", ],
		//'' => ['ja' => "", 'en' => "", ],
	];

	try{
		$exif = exif_read_data($_FILES['img']['tmp_name']);
                if ($exif['Model'] != 'Nintendo 3DS') throw new Exception(s($s['err_exif']));
                if (strlen($exif['Software']) != 5) throw new Exception(s($s['err_exif']));
            	$soft_id = '3D'.$exif['Software'];
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
