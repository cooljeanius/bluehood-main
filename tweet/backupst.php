<?php
	include('/var/www/twiverse.php');
	$s = [
		'notice' => [
			'ja' => "下書き保存しました。\n投稿画面を開くと下書きが自動的に読み込まれます。",
			'en' => "Draft saved. \nWhen you open the post-creation screen, the draft will load automatically.",
		],
		'err' => ['ja' => "エラーが発生しました。\n", 'en' => "An error occurred.", ],
		//'' => ['ja' => "", 'en' => "", ],
	];

	try{
		mysql_start();
		mysql_throw(mysql_query("update user set draft_stamp = '".mysql_escape_string($_POST['stamp'])."' where id=".$_SESSION['twitter']['id']));
		mysql_close();
		echo "下書き保存しました。\n投稿画面を開くと下書きが自動的に読み込まれます。";
	}catch(Exception $e){
		http_response_code(500);
		echo "エラーが発生しました。\n";
		echo $e->getMessage();
	}
?>
