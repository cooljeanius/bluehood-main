<?php
	include('/var/www/twiverse.php');

	unset($soft_id);
	unset($comm_id);
	unset($name);
	$image = file_get_contents('noimage.jpg');
	if ($_FILES['selimg']['name'] != ''){
		unset($name);
		unset($comm_id);
		unset($soft_id);
		$exif = exif_read_data($_FILES['selimg']['tmp_name']);
		if (useragent() == 'wiiu'){
			$soft_id = 'WU'.substr($_FILES['selimg']['name'], -9, 5);
		}else if ($exif['Model'] == 'Nintendo 3DS') do{
			if (strlen($exif['Software']) != 5){
				//die('<div id="err">この画像は使用できません。</div>');
				$comm_id = 'default0';
				$name = '未分類';
				break;
			}
			$soft_id = '3D'.$exif['Software'];
		}while(0); else if ($exif['Model'] == 'PlayStation(R)Vita'){
			$soft_id = 'PV'.substr(strstr($exif['MakerNote'], 'GPNM'), 5, 9);
			$name = mb_substr(twitter_trimhash(mysql_escape_string(substr(strstr($exif['MakerNote'], 'ALBM'), 5, -1))), 0, 25);

			mysql_start();
                        $res = mysql_fetch_assoc(mysql_query("select name from soft_id2name where id = '".$soft_id."'"));
			mysql_close();
                        if (!$res['name']) make_comm($soft_id, $name);
		}else if ($exif['Model'] == 'NintendoDS'){
			if (strlen($exif['Software']) != 4){
                                //die('<div id="err">この画像は使用できません。</div>');
                                $comm_id = 'default0';
                                $name = '未分類';
                                break;
                        }
                        $soft_id = 'DS'.$exif['Software'];
		}else if ($exif['Model'] == 'PlayStation(R)4'){
                        $soft_id = 'P4'.substr(strstr($exif['MakerNote'], 'GPNM'), 5, 9);
                        $name = mb_substr(twitter_trimhash(mysql_escape_string(substr(strstr($exif['MakerNote'], 'ALBM'), 5, -1))), 0, 25);

                        mysql_start();
                        $res = mysql_fetch_assoc(mysql_query("select name from soft_id2name where id = '".$soft_id."'"));
                        mysql_close();
                        if (!$res['name']) make_comm($soft_id, $name);
                }else if (0){	// AI ディテクタ
		}else{
			/*die('<div id="err">Wii U、3DS以外の端末からはスクリーンショットを投稿できません。</div>');*/
			$comm_id = 'default0';
			$name = '未分類';
		}

		$image = file_get_contents($_FILES['selimg']['tmp_name']);
		$_SESSION['post_image'] = base64_encode($image);
	}else if (isset($_POST['selalbum'])){
		$twitter = twitter_start();
		$status = $twitter->get('statuses/show', ['id' => $_POST['selalbum']]);
		$texts = explode(' ', str_replace('@home ', '', $status->text));
		if (hash('crc32b', $texts[0]) != $texts[1]) die('<div id="err">ソフト情報を認識できないため、このアルバムは使用できません。</div>');
		$soft_id = $texts[0];
		if (strtotime($status->created_at) < 1516037892) $soft_id = 'WU'.$soft_id;
		$image = file_get_contents($status->entities->media[0]->media_url_https);
		$_SESSION['post_image'] = base64_encode($image);
	}else{	// 画像を外す
		unset($_SESSION['post_image']);
	}

	if ((!isset($comm_id))&&(isset($soft_id))){
		mysql_start();
               	$res = mysql_fetch_assoc(mysql_query("select name from soft_id2name where id = '".$soft_id."'"));
		if ($res['name']){	/* ゲームが登録済み */
			//die('<div id="err">'.$soft_id.'登録あり</div>');
        	        $res = mysql_fetch_assoc(mysql_query("select id from comm where soft_id = '".$soft_id."'"));
			$comm_id = $res['id'];

			$res = mysql_fetch_assoc(mysql_query("select name from comm where id = '".$comm_id."'"));
		        $name = $res['name'];
		}else{	/* ゲームが未登録 */
			//die('<div id="err">'.$soft_id.'登録なし</div>');
			unset($comm_id);

	                $res = mysql_fetch_assoc(mysql_query("select default_name from soft_id2name where id = '".$soft_id."'"));
	                if ($res['default_name']) $name = $res['default_name'];
			else $name = '';
		}
       	        mysql_close();
	}

	echo '<div id="data">'.base64_encode($image).'</div>';
	if (isset($soft_id)) echo '<div id="soft_id">'.$soft_id.'</div>';
	if (isset($comm_id)) echo '<div id="id">'.$comm_id.'</div>';
	if (isset($name)) echo '<div id="name">'.$name.'</div>';

	/* レスポンス
	1. err -> エラー出力
	2. comm_idあり、コミュニティ名、image -> コミュニティ選択済み
	3. ソフト名、image -> コミュニティ作成
	4. image -> 画像を外す
	*/
?>
