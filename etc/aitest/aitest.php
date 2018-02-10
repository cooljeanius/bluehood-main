<?php
	include('/var/www/twiverse.php');
	twitter_start();	// 外部攻撃対策

	// リクエスト用のJSONを作成
	$json = json_encode( array(
		"requests" => array(
			array(
				"image" => array(
					"content" => base64_encode( file_get_contents($_FILES['selimg']['tmp_name']) ) ,
				) ,
				"features" => array(
					/*array(
						"type" => "FACE_DETECTION" ,
						"maxResults" => 3 ,
					) ,*/
					array(
						"type" => "WEB_DETECTION" ,
						"maxResults" => 5 ,
					) ,
					/*array(
						"type" => "LANDMARK_DETECTION" ,
						"maxResults" => 3 ,
					) ,*/
					/*array(
						"type" => "LOGO_DETECTION" ,
						"maxResults" => 3 ,
					) ,*/
					/*array(
						"type" => "LABEL_DETECTION" ,
						"maxResults" => 3 ,
					) ,*/
					/*array(
						"type" => "TEXT_DETECTION" ,
						"maxResults" => 3 ,
					) ,*/
					/*array(
						"type" => "SAFE_SEARCH_DETECTION" ,
						"maxResults" => 3 ,
					) ,*/
					/*array(
						"type" => "IMAGE_PROPERTIES" ,
						"maxResults" => 3 ,
					) ,*/
				) ,
			) ,
		) ,
	) ) ;

	// リクエストを実行
	$curl = curl_init() ;
	curl_setopt( $curl, CURLOPT_URL, "https://vision.googleapis.com/v1/images:annotate?key=".GOOGLE_CLOUD_PLATFORM_KEY) ;
	curl_setopt( $curl, CURLOPT_HEADER, true ) ; 
	curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "POST" ) ;
	curl_setopt( $curl, CURLOPT_HTTPHEADER, array( "Content-Type: application/json" ) ) ;
	curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false ) ;
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true ) ;
	if( isset($referer) && !empty($referer) ) curl_setopt( $curl, CURLOPT_REFERER, $referer ) ;
	curl_setopt( $curl, CURLOPT_TIMEOUT, 15 ) ;
	curl_setopt( $curl, CURLOPT_POSTFIELDS, $json ) ;
	$res1 = curl_exec( $curl ) ;
	$res2 = curl_getinfo( $curl ) ;
	curl_close( $curl ) ;

	// 取得したデータ
	$json = substr( $res1, $res2["header_size"] ) ;				// 取得したJSON
	$header = substr( $res1, 0, $res2["header_size"] ) ;		// レスポンスヘッダー

	// 出力
	//echo "<h2>JSON</h2>" ;
	//echo $json ;
	//echo "<h2>ヘッダー</h2>" ;
	//echo $header ;

	$res = json_decode($json);
	//print_r($res);
	?>この画像のハッシュタグは……<ul><?php
	foreach($res->responses[0]->webDetection->webEntities as $webentity){
		$hashtag = '#'.twitter_trimhash(strtolower($webentity->description));
		?><li><a href="https://twitter.com/search?<?php echo http_build_query(['q' => $hashtag]); ?>"><?php echo $hashtag; ?></a> (スコア: <?php echo $webentity->score; ?>)</li><?php
	}
	?></ul>と判定されました。<?php

        mb_language("Japanese");
        mb_internal_encoding("UTF-8");
        mb_send_mail(MAIL_TO, '画像認識テスト', '画像認識テスト', "From: ".MAIL_FROM."\nContent-Type: text/plain");
?>
