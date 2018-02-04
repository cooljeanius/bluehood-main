<?php
	include('/var/www/twiverse.php');
	$s = [
		'err_name' => ['ja' => "ソフト名を入力してください。", 'en' => "Please enter software's name. ", ],
		'err_length' => ['ja' => "ソフト名を25文字以内で入力してください。", 'en' => "Software's name is max 25 characters. ", ],
		'err' => ['ja' => "エラーが発生しました。", 'en' => "An error was occured. ", ],
		//'' => ['ja' => "", 'en' => "", ],
	];

	try{
		$_POST['soft_id'] = mysql_escape_string($_POST['soft_id']);
		$_POST['name'] = mysql_escape_string($_POST['name']);

		$_POST['name'] = twitter_trimhash($_POST['name']);

		if ($_POST['name'] == ''){
			die(json_encode(['err' => s($s['err_name'])]));
		}
		if (mb_strlen($_POST['name']) > 25){
			die(json_encode(['err' => s($s['err_length'])]));
		}

		$id = make_comm($_POST['soft_id'], $_POST['name']);
		die(json_encode(['id' => $id, 'name' => $_POST['name']]));
	}catch(Exception $e){
		die(json_encode(['err' => s($s['err'])."\n".$e->getMessage()]));
	}
?>
