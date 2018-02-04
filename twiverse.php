<?php
	// サイト情報
	define('DOMAIN', 'https://twiverse.net');
	define('ROOT_PATH', '/var/www/html/');
	define('ROOT_URL', '/');

	// MySQL ログイン情報
	define('MYSQL_ADDR', 'localhost');
	define('MYSQL_USER', 'username');
	define('MYSQL_PASS', 'password');

	// 不具合報告などで使うメール情報
        define('MAIL_FROM', '');	// Twiverse が使うメールアドレス
        define('MAIL_TO', '');	// メールの送信先 (つまり、Twiverse 管理者)

	// Twiverse の 「Twitter アプリ」(REST API で使用)
        define('CONSUMER_KEY', '');
        define('CONSUMER_SECRET', '');

	// Twiverse Reader アプリ (未ログインでのツイート表示などに使用)
	define(READER_KEY, '');
        define(READER_SECRET, '');
        define(READER_COMSUMER_KEY, '');
        define(READER_COMSUMER_SECRET, '');

	// Twiverse Admin アプリ (Twiverse 管理者の特権で行う REST API)
	define('ADMIN_KEY', '');
        define('ADMIN_SECRET', '');
        define('ADMIN_COMSUMER_KEY', '');
        define('ADMIN_COMSUMER_SECRET', '');

	require_once ROOT_PATH.'common.php';
?>
