<?php
	include('/var/www/twiverse.php');
	$twitter = twitter_start();
	$option = ['#情報交換 ', '#コンテスト ', '#対戦 ' ,'#大会 ' ,'#協力 ', '#質問 '];

		if (isset($_POST['comm_id'])){
	                mysql_start();
        	        $res = mysql_fetch_assoc(mysql_query("select collection_id from comm where id = '".$_POST['comm_id']."'"));
        	        $collection_id = $res['collection_id'];
        	        mysql_close();
		}else $collection_id = '932243624233979905';

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

		/*$excwords = ['Twiverse_draw', 'Twiverse_diary'];
		foreach($hashcnts as $text => $cnt){
			foreach($excwords as $excword) if (strcasecmp($text, $excword) == 0){
				unset($hashcnts[$text]);
				break;
			}
		}*/
		arsort($hashcnts);

		foreach($hashcnts as $text => $cnt){
			array_push($option, '#'.$text.' ');
		}

	/*$follows = $twitter->get('friends/list', ['screen_name' => $_SESSION['twitter']['account']['user']->screen_name, 'count' => 200])->users;
        foreach($follows as $user){
		array_push($option, '@'.$user->screen_name.' ');
        }*/
	die(json_encode(['option' => $option]));
?>
