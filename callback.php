<?php
	include('/var/www/twiverse.php');
	$s = [
		'auth_err' => [
			'ja' => "認証エラー！<br>新ドメイン<a href="https://twiverse.net/">twiverse.net</a>でのアクセスをお試しください。",
			'en' => "Authentication error! <br> Please try accessing the new domain <a href="https://twiverse.net/">twiverse.net</a> instead.",
		],
		//'' => ['ja' => "", 'en' => "", ],
	];

	require_once 'common.php';
	require_once 'twitteroauth/autoload.php';
	use Abraham\TwitterOAuth\TwitterOAuth;

	if (isset($_REQUEST['oauth_token']) && $_SESSION['oauth_token'] !== $_REQUEST['oauth_token']) {
		die('認証エラー！<br>新ドメイン<a href="https://twiverse.net/">twiverse.net</a>でのアクセスをお試しください。');
	}

	$conn = new TwitterOAuth(CONSUMER_KEY,CONSUMER_SECRET, $_SESSION['oauth_token'], $_SESSION['oauth_token_secret']);
	$access_token = $conn->oauth('oauth/access_token', array('oauth_verifier' => $_REQUEST['oauth_verifier']));
	$_SESSION['access_token'] = $access_token['oauth_token'];
	$_SESSION['access_secret'] = $access_token['oauth_token_secret'];

	//$twitter = twitter_start();
	header( 'location: '. $_SESSION['callback_referer']);
	//header( 'location: '. DOMAIN.ROOT_URL.'guide.php');
?>
