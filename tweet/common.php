<?php
	include('/var/www/twiverse.php');

	function dropTweet($status, $twitter, $hide, $comm_ids){
		try{
			mysql_start();
			$res = mysql_query("select post_register from user where screen_name='".$_SESSION['twitter']['screen_name']."'"); mysql_throw();
			$set = mysql_fetch_assoc($res); mysql_throw();
			if ($set['post_register']){
				$hide = $hide?'true':'false';
				mysql_query("insert into tweet (id, screen_name, hide, time, comm_id) values (".$status->id.", '".$status->user->screen_name."', ".$hide.", now(), '".$comm_id."')"); mysql_throw();
				$twitter_admin = twitter_admin();
				//Communities
				foreach($comm_ids as $comm_id){
					$res = mysql_fetch_assoc(mysql_throw(mysql_query("select collection_id from comm where id = '".$comm_id."'")));
					$collection_id = $res['collection_id'];
					$twitter_admin->post('collections/entries/add', ['id' => 'custom-'.$collection_id, 'tweet_id' => $status->id_str]);
					mysql_query("update comm set post_n=post_n+1 where id='".$comm_id."'"); mysql_throw();
				}
				// All Posts
				$twitter_admin->post('collections/entries/add', ['id' => 'custom-'.ALL_POSTS, 'tweet_id' => $status->id_str]);
				// マイページ
				if (isset($_SESSION['twitter']['account']['collection_id'])) $twitter->post('collections/entries/add', ['id' => $_SESSION['twitter']['account']['collection_id'], 'tweet_id' => $status->id_str]);
			}
			mysql_close();
		}catch(Exception $e){
			catch_default($e);
		}
	}

	function complete_page($comm_ids){
		header('Location: '.DOMAIN.ROOT_URL);
		if (!isset($comm_id)) header('Location: '.DOMAIN.ROOT_URL.'view/');

		mysql_start();
		$res = mysql_fetch_assoc(mysql_query("select name, list_id from comm where id = '".$comm_id."'"));
		$comm_name = $res['name'];
		$list_id = $res['list_id'];
		mysql_close();

		$twitter = twitter_start();
		$registered = isset($twitter->get('lists/members/show', ['list_id' => $list_id, 'screen_name' => $_SESSION['twitter']['account']['user']->screen_name, 'include_entities' => 'false', 'skip_status' => 'true'])->id);

		if ($registered) header('Location: '.DOMAIN.ROOT_URL.'view/?comm_id='.$comm_id);

		$s = [
			'title' => ['ja' => '送信しました！', 'en' => 'Sent! '],
			'invite' => ['ja' => "
				あなたのアカウントをTwitter ".$comm_name." リストに登録しませんか？<br>
                                登録すると、以降はスクリーンショットを添付せずに<br>
                                ".$comm_name." コミュニティに投稿できるようになります！<br>
                                <br>
                                リストは公開設定です。
			", 'en' => "
				Would you like to reqister your account to Twitter ".$comm_name." list? <br>
                                If you registered, you can post to ".$comm_name." community without screenshots! <br>
                                <br>
                                The list is set to public. 
			"],
			'register' => ['ja' => "登録する", 'en' => "Register"],
			'registered' => ['ja' => "登録しました！", 'en' => "Registered! "],
		];
			?>
<!DOCTYPE html>
<html>
	<?php head(); ?>
	<body>
		<h2 class="topbar">送信しました！</h2>
		<div class="main paddingleft paddingright">
			<br>
			<div class="whitebox">
				<p><?php l($s['invite']); ?><!--<br>
				※登録者数が5000人を超えるコミュニティの場合、<br>
				登録が外れてしまう場合があります。--></p>
				<a id="register" class="linkbutton"><?php l($s['register']); ?></a><br>
				<a class="linkbutton" href="<?php echo DOMAIN.ROOT_URL.'view/?comm_id='.$comm_id; ?>">コミュニティに移動</a>

				<script type="text/javascript">
					$('#register').click(function(){
						$.post(tweet_url + 'reglist.php', {id: '<?php echo $list_id; ?>', screen_name: '<?php echo $_SESSION['twitter']['account']['user']->screen_name; ?>'});
						$(this).css('background-color', 'lightgray');
						$(this).css('color', 'inherit');
						$(this).html('<?php l($s['registered']); ?>');
						$(this).prop("disabled", true);
					});
				</script>
			</div>
		</div>
	</body>
</html>
		<?php
	}

	function detect($filename, $selalbum = null){
	$twitter = twitter_start();

	/* 画像 -> soft_id, $_SESSION['post_image'], $vision_res */
	if ($filename){
		$_SESSION['post_image'] = base64_encode(file_get_contents($filename));
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
		$vision_res = json_decode($json);

		function detector_wiiu($filename){
			$soft_ids = [];
			if (useragent() == 'wiiu'){
				$soft_ids []= 'WU'.substr($filename, -9, 5);
			}
			return $soft_ids;
		}
		function detector_3ds($exif){
			$soft_ids = [];
			if ($exif['Model'] == 'Nintendo 3DS'){
				if (strlen($exif['Software']) == 5){
					$soft_ids []= '3D'.$exif['Software'];
				}
			}
			return $soft_ids;
		}
		function detector_psvita($exif){
			$soft_ids = [];
			if ($exif['Model'] == 'PlayStation(R)Vita'){
				$soft_ids []= 'PV'.substr(strstr($exif['MakerNote'], 'GPNM'), 5, 9);
				$name = mb_substr(twitter_trimhash(mysql_escape_string(substr(strstr($exif['MakerNote'], 'ALBM'), 5, -1))), 0, 25);

	                        $res = mysql_fetch_assoc(mysql_query("select name from soft_id2name where id = '".mysql_escape_string($soft_id)."'"));
	                        if (!$res['name']) make_comm($soft_id, $name);
			}
			return $soft_ids;
		}
		function detector_ds($exif){
			$soft_ids = [];
			if ($exif['Model'] == 'NintendoDS'){
				if (strlen($exif['Software']) == 4){
		                        $soft_ids []= 'DS'.$exif['Software'];
	                        }
			}
			return $soft_ids;
		}
		function detector_ps4($exif){
			$soft_ids = [];
			if ($exif['Model'] == 'PlayStation(R)4'){
        	                $soft_ids []= 'P4'.substr(strstr($exif['MakerNote'], 'GPNM'), 5, 9);
        	                $name = mb_substr(twitter_trimhash(mysql_escape_string(substr(strstr($exif['MakerNote'], 'ALBM'), 5, -1))), 0, 25);

	                        $res = mysql_fetch_assoc(mysql_query("select name from soft_id2name where id = '".mysql_escape_string($soft_id)."'"));
	                        if (!$res['name']) make_comm($soft_id, $name);
	                }
			return $soft_ids;
		}
		function detector_ai($vision_res){
			$soft_ids = [];
			foreach($vision_res->responses[0]->webDetection->webEntities as $webentity){
				$soft_id = 'AI'.$webentity->entityId;
				$soft_ids []= $soft_id;
				mysql_query("insert into soft_id2name (id, name) values ('$soft_id', '".mysql_escape_string(twitter_trimhash($webentity->description))."')");
			}
			return $soft_ids;
		}

		$soft_ids = [];
		$exif = exif_read_data($filename);

		mysql_start();
		$soft_ids = $soft_ids + detector_wiiu($filename);
		$soft_ids = $soft_ids + detector_3ds($exif);
		$soft_ids = $soft_ids + detector_psvita($exif);
		$soft_ids = $soft_ids + detector_ds($exif);
		$soft_ids = $soft_ids + detector_ps4($exif);
		$soft_ids = $soft_ids + detector_ai($vision_res);
		mysql_close();

	}else if (isset($selalbum)){
		$twitter = twitter_start();
		$status = $twitter->get('statuses/show', ['id' => $selalbum]);
		$texts = explode(' ', str_replace('@home ', '', $status->text));
		if (hash('crc32b', $texts[0]) == $texts[1]){
			$soft_ids = [$texts[0]];
			if (strtotime($status->created_at) < 1516037892) $soft_ids[0] = 'WU'.$soft_ids[0];
		}
		$image = file_get_contents($status->entities->media[0]->media_url_https);
		$_SESSION['post_image'] = base64_encode($image);
	}else{	// 画像を外す
		unset($_SESSION['post_image']);
	}

	/* soft_ids -> comms */
	$comms = [];
	mysql_start();
	foreach($soft_ids as $soft_id){
		$res = mysql_throw(mysql_query("select id, name from comm where soft_id='$soft_id'"));
		while($comm = mysql_fetch_assoc($res)){
			$comms []= $comm;
		}
	}
	mysql_close();

	/* $_SESSION['post_image'], $comms -> option */
	$option = [];
	foreach($vision_res->responses[0]->webDetection->webEntities as $webentity){	// Vision API
		$hashtag = '#'.twitter_trimhash($webentity->description);
		array_push($option, $hashtag.' ');
	}
        mysql_start();
	foreach($comms as $comm){
	       	$res = mysql_fetch_assoc(mysql_throw(mysql_query("select collection_id from comm where id = '".$comm['id']."'")));
	       	$collection_id = $res['collection_id'];

		$res = $twitter->get('collections/entries', ['id' => 'custom-'.$collection_id, 'count' => '200']);
		twitter_throw();
		$search = $res->objects->tweets;
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
      	mysql_close();
	/*$follows = $twitter->get('friends/list', ['screen_name' => $_SESSION['twitter']['account']['user']->screen_name, 'count' => 200])->users;
        foreach($follows as $user){
		array_push($option, '@'.$user->screen_name.' ');
        }*/

	$ret = [];
	if (isset($_SESSION['post_image'])){
		$ret['image'] = $_SESSION['post_image'];
	}else{
		$ret['image'] = base64_encode(file_get_contents('noimage.jpg'));
	}
	$ret['option'] = $option;
	$ret['comms'] = $comms;

	return $ret;
	}

?>
