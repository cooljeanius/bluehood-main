<?php
	include('/var/www/twiverse.php');
	$s = [
		//'' => ['ja' => "", 'en' => "", ],
	];

	try{
		$twitter = twitter_start();	// screen_name 認証のため必要 (required for authentication)

		mysql_start();

		$res = mysql_query("select * from selstamp where screen_name = '".$_SESSION['twitter']['screen_name']."'");
		mysql_throw();
                if (mysql_num_rows($res) >= 10) throw new Exception('手持ちは 10 個以上追加できません。');

		mysql_query("insert into selstamp (screen_name, image_url) values('".$_SESSION['twitter']['screen_name']."', '".mysql_escape_string($_GET['image_url'])."')");
		mysql_throw();

		mysql_close();
	}catch(Exception $e){
		catch_default($e);
	}

	header('Location: '.DOMAIN.ROOT_URL.'view/stamp/');
?>

