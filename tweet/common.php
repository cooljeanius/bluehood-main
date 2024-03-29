<?php
	include('/var/www/twiverse.php');
	$s = [
		//'' => ['ja' => "", 'en' => "", ],
	];
	//oh wait there's another one of these later...

	function dropTweet($status, $twitter, $hide, $comm_ids){
		try{
			mysql_start();
			$res = mysql_query("select * from user where id=".$_SESSION['twitter']['id']); mysql_throw();
			$set = mysql_fetch_assoc($res); mysql_throw();
			if ($set['post_register']){
				$hide = $hide?'true':'false';
				$comm_id_string = '';
				foreach($comm_ids as $comm_id){
					$comm_id_string .= $comm_id.',';
				}
				$comm_id_string = rtrim($comm_id_string, ',');
				mysql_query("insert into tweet (id, screen_name, hide, time, comm_id) values (".$status->id.", '".$status->user->screen_name."', ".$hide.", now(), '".$comm_id_string."')"); mysql_throw();
				$twitter_admin = twitter_admin();
				//Communities
				foreach($comm_ids as $comm_id){
					$res = mysql_fetch_assoc(mysql_throw(mysql_query("select soft_id, list_id, collection_id from comm where id = '".$comm_id."'")));
					$collection_id = $res['collection_id'];
					$twitter_admin->post('collections/entries/add', ['id' => 'custom-'.$collection_id, 'tweet_id' => $status->id_str]);
					mysql_query("update comm set post_n=post_n+1 where id='".$comm_id."'"); mysql_throw();

					$detector = detector($res['soft_id']);	/* Twitter List */
					if ($set['list_'.$detector['prefix']]){
						$registered = isset(/*twitter_throw(*/$twitter->get('lists/members/show', ['list_id' => $res['list_id'], 'screen_name' => $_SESSION['twitter']['screen_name'], 'include_entities' => 'false', 'skip_status' => 'true'])/*)*/->id);
						if (!$registered){
							$twitter_admin->post('lists/members/create', ['list_id' => $res['list_id'], 'screen_name' => $_SESSION['twitter']['screen_name']]);
							twitter_throw();
							mysql_throw(mysql_query("update comm set list_n=list_n+1 where list_id='".$res['list_id']."'"));
						}
					}
				}
				// All Posts
				$twitter_admin->post('collections/entries/add', ['id' => 'custom-'.ALL_POSTS, 'tweet_id' => $status->id_str]);
				// マイページ (my page)
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

	function detect($file, $selalbum = null){
	$filename = $file['tmp_name'];
	$twitter = twitter_start();
	$msg = '';

	/* 画像 (image) -> soft_id, $_SESSION['post_image']*/
	if ($filename){
		$_SESSION['post_image'] = base64_encode(file_get_contents($filename));

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
				$name = mb_substr(twitter_trimhash(mysql_escape_string(substr(strstr($exif['MakerNote'], 'ALBM'), 5, -1))), 0, 23);
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
        	                $soft_id = 'P4'.substr(strstr($exif['MakerNote'], 'GPNM'), 5, 9);
        	                $name = mb_substr(twitter_trimhash(mysql_escape_string(substr(strstr($exif['MakerNote'], 'ALBM'), 5, -1))), 0, 23);
	                        $res = mysql_fetch_assoc(mysql_query("select name from soft_id2name where id = '".mysql_escape_string($soft_id)."'"));
	                        if (!$res['name']) make_comm($soft_id, $name);
				$soft_ids []= $soft_id;
	                }
			return $soft_ids;
		}
		function detector_model($exif){
			$soft_ids = [];
			if ($exif['Model']){
        	                $name = mb_substr(twitter_trimhash(mysql_escape_string($exif['Model'])), 0, 23);
				$res = mysql_fetch_assoc(mysql_throw(mysql_query("select * from soft_id2name where name='$name' and id like 'MD%'")));
				if ($res['id']){
					$soft_id = $res['id'];
				}else{
					$soft_id = 'MD'.substr(uniqid(), 0, 8);
					make_comm($soft_id, $name);
				}
				$soft_ids []= $soft_id;

	                }
			return $soft_ids;
		}
		function detector_ai($other_ids){
			$soft_ids = [];

			foreach($other_ids as $other_id){
				$detector = substr($other_id, 0, 2);
				if (($detector=='3D')||($detector=='DS')||($detector=='PV')||($detector=='P4')||($detector=='WU')) return [];
			}

			// リクエスト用のJSONを作成 (Create JSON for request)
			$json = json_encode( array(
				"requests" => array(
					array(
						"image" => array(
							"content" => $_SESSION['post_image'],
						) ,
						"features" => array(
							array(
								"type" => "WEB_DETECTION" ,
								"maxResults" => 1,
							) ,
						) ,
					) ,
				) ,
			) ) ;
			// リクエストを実行 (Execute request)
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
			// 取得したデータ (The acquired data)
			$json = substr( $res1, $res2["header_size"] ) ; // 取得したJSON (The obtained JSON)
			$header = substr( $res1, 0, $res2["header_size"] ) ; // レスポンスヘッダー (Response header)
			$vision_res = json_decode($json);

			foreach($vision_res->responses[0]->webDetection->webEntities as $webentity){
				$soft_id = 'AI'.$webentity->entityId;
				$soft_ids []= $soft_id;

                	        $res = mysql_fetch_assoc(mysql_query("select name from comm where soft_id = '".mysql_escape_string($soft_id)."'"));
                	        if (!$res['name']) make_comm($soft_id, mb_substr(twitter_trimhash(mysql_escape_string($webentity->description)), 0, 23) );
			}
			return $soft_ids;
		}

		$soft_ids = [];
		$exif = exif_read_data($filename);

		mysql_start();
		$set = mysql_fetch_assoc(mysql_throw(mysql_query("select * from user where id=".$_SESSION['twitter']['id'])));
		if ($set['en_WU']) $soft_ids = array_merge_recursive($soft_ids, detector_wiiu($file['name']));
		if ($set['en_3D']) $soft_ids = array_merge_recursive($soft_ids, detector_3ds($exif));
		if ($set['en_PV']) $soft_ids = array_merge_recursive($soft_ids, detector_psvita($exif));
		if ($set['en_DS']) $soft_ids = array_merge_recursive($soft_ids, detector_ds($exif));
		if ($set['en_P4']) $soft_ids = array_merge_recursive($soft_ids, detector_ps4($exif));
		if ($set['en_MD']) $soft_ids = array_merge_recursive($soft_ids, detector_model($exif));
		if ($set['en_AI']) $soft_ids = array_merge_recursive($soft_ids, detector_ai($soft_ids)); /* AI ディテクタは最後に行う (Do the AI detector last) */
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
	}else{	// 画像を外す (Remove images)
		unset($_SESSION['post_image']);
	}

	/* soft_ids -> comms */
	$comms = [];
	mysql_start();
	foreach($soft_ids as $soft_id){
		$res = mysql_throw(mysql_query("select id, name from comm where soft_id='$soft_id'"));
		$detector = detector($soft_id);
		if (mysql_num_rows($res)){
			while($comm = mysql_fetch_assoc($res)){
				$comm['detector'] = $detector['name'];
				$comms []= $comm;
			}
		}else{
			$msg .= $detector['name']." ディテクターのコミュニティが見つかりませんでした。\n専用ページよりコミュニティを設立する必要があります。\nソフトID: ".$soft_id."\n";
		}
	}
	mysql_close();

	/* $_SESSION['post_image'], $comms -> option */
	$option = [];
	/*foreach($vision_res->responses[0]->webDetection->webEntities as $webentity){	// Vision API
		$hashtag = '#'.twitter_trimhash($webentity->description);
		array_push($option, $hashtag.' ');
	}*/
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
		$ret['image'] = base64_decode($_SESSION['post_image']);
	}else{
		$ret['image'] = file_get_contents('noimage.jpg');
	}
	$src = imagecreatefromstring($ret['image']);
	$width = imagesx($src);
	$height = imagesy($src);
	$dst_width = (int)$width*(96.0/$height);
	$dst = imagecreatetruecolor($dst_width, 96);
	imagecopyresampled($dst, $src, 0, 0, 0, 0, $dst_width, 96, $width, $height);
	ob_start();
	imagejpeg($dst, null, 90);
	$ret['image'] = base64_encode(ob_get_contents());
	ob_end_clean();
	$ret['option'] = $option;
	$ret['comms'] = $comms;
	if ($msg != '') $ret['msg'] = $msg;

	return $ret;
	}

?>
