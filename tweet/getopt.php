<?php
	include('/var/www/twiverse.php');
	$twitter = twitter_start();

	try{
	$option = [];
	if (isset($_POST['comm_id'])){
                mysql_start();
       	        $res = mysql_fetch_assoc(mysql_query("select collection_id from comm where id = '".$_POST['comm_id']."'"));
		mysql_throw();
       	        $collection_id = $res['collection_id'];
       	        mysql_close();
		$search = $twitter->get('collections/entries', ['id' => 'custom-'.$collection_id, 'count' => '200'])->objects->tweets;
		$hashcnts = [];
		$topstatus = [];
		foreach($search as $status){
			foreach($status->entities->hashtags as $hashtag){
				if (!$hashcnts[$hashtag->text]){
					$hashcnts[$hashtag->text] = 0;
					$topstatus[$hashtag->text] = $status;
				}
				$hashcnts[$hashtag->text]++;
			}
		}
		arsort($hashcnts);
		foreach($hashcnts as $text => $cnt){
			array_push($option, '#'.$text.' ');
		}
	}

	// リクエスト用のJSONを作成
	$json = json_encode( array(
		"requests" => array(
			array(
				"image" => array(
					"content" => $_SESSION['post_image'],
				) ,
				"features" => array(
					array(
						"type" => "WEB_DETECTION" ,
						"maxResults" => 5 ,
					) ,
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
	$res = json_decode($json);
	foreach($res->responses[0]->webDetection->webEntities as $webentity){
		$hashtag = '#'.twitter_trimhash(strtolower($webentity->description));
		array_push($option, $hashtag.' ');
	}

	/*$follows = $twitter->get('friends/list', ['screen_name' => $_SESSION['twitter']['account']['user']->screen_name, 'count' => 200])->users;
        foreach($follows as $user){
		array_push($option, '@'.$user->screen_name.' ');
        }*/

	die(json_encode(['option' => $option]));
	}catch(Exception $e){
		die(json_encode(['error' => $e->getMessage()]));
	}
?>

