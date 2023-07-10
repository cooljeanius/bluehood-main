<?php
	// サイト情報 (Site information)
	define('DOMAIN', 'https://twiverse.net');
	define('ROOT_PATH', '/var/www/html/');
	define('ROOT_URL', '/');

	// MySQL ログイン情報 (MySQL login information)
	define('MYSQL_ADDR', 'localhost');
	define('MYSQL_USER', 'username');
	define('MYSQL_PASS', 'password');

	// 不具合報告などで使うメール情報 (Mail information used for troubleshooting etc.)
    define('MAIL_FROM', ''); // Twiverse が使うメールアドレス (E-mail address used by Twiverse)
    define('MAIL_TO', ''); // メールの送信先 (つまり、Twiverse 管理者) (Mail destination (e.g., Twiverse administrator))

	// Twiverse の 「Twitter アプリ」(REST API で使用) (Twiverse's "Twitter application" (used with REST API))
        define('CONSUMER_KEY', '');
        define('CONSUMER_SECRET', '');

	// Twiverse Reader アプリ (未ログインでのツイート表示などに使用) (Twiverse Reader application (used for displaying tweets when not logged in))
	define(READER_KEY, '');
        define(READER_SECRET, '');
        define(READER_COMSUMER_KEY, '');
        define(READER_COMSUMER_SECRET, '');

	// Twiverse Admin アプリ (Twiverse 管理者の特権で行う REST API) (Twiverse Admin application (REST API done with Twiverse administrator's privileges))
	define('ADMIN_KEY', '');
        define('ADMIN_SECRET', '');
        define('ADMIN_COMSUMER_KEY', '');
        define('ADMIN_COMSUMER_SECRET', '');

	define('GOOGLE_CLOUD_PLATFORM_KEY', ''); // Google Cloud Platform の API キー (Google Cloud Platform API key)

	require_once ROOT_PATH.'common.php';
?>
