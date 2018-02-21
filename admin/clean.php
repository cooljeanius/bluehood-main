<?php
	include('/var/www/twiverse.php');

	try{
		$twitter_admin = twitter_admin();
		mysql_start();
			$collections = $twitter_admin->get('collections/list', ['screen_name' => 'bluehood_admin', count => '200']);
			$coll_ids = [];
			foreach($collections->response->results as $collection){
				$coll_ids []= (int)str_replace('custom-', '', $collection->timeline_id);
			}
			var_dump($coll_ids);

			$res = mysql_query("select * from comm");
			var_dump($res);
			while($comm = mysql_fetch_assoc($res)){
				var_dump((int)$comm['collection_id']);
				$i = array_search((int)$comm['collection_id'], $coll_ids);
				if ($i !== false){
					unset($comm_ids[$i]);
				}
			}
			echo "<br><br>";
			var_dump($coll_ids);
		mysql_close();
	}catch(Exception $e){
		catch_default($e);
	}
?>
