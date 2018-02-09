<?php
	include('/var/www/twiverse.php');
	unset($_SESSION['collection_cursor']);
	$twitter = twitter_start();	// 手もちスタンプの管理のため、ログイン必要

	$s = [
		'title' => ['ja' => "スタンプ", 'en' => "Stamp", ],
		'make' => ['ja' => "スタンプを作る", 'en' => "Create a stamp", ],
		'selected' => ['ja' => "手持ちのスタンプ", 'en' => "Selected stamps", ],
		'add' => ['ja' => "追加", 'en' => "Add", ],
		'remove' => ['ja' => "削除", 'en' => "Remove", ],
		//'' => ['ja' => "", 'en' => "", ],
	];
?>
<!DOCTYPE html>
<html>
	<head>
	</head>
	<?php head(); ?>
	<body>
		<div class="topbar"><?php l($s['title']); ?></div>
		<div class="main">
			<div class="header">
				<a href="<?php echo ROOT_URL; ?>tweet/stamp/" style="float: right; "><button><?php l($s['make']); ?></button></a>
				<div style="text-align: center; "><?php l($s['selected']); ?><br>
					<?php
						mysql_start();
						$res = mysql_query("select image_url from selstamp where screen_name = '".$_SESSION['twitter']['screen_name']."'");
						if (mysql_num_rows($res) == 0){
							?>スタンプはありません。追加してみよう！<?php
						}else while($stamp = mysql_fetch_assoc($res)){
							?><div style="display: inline-block; ">
								<div class="card"><img src="<?php echo $stamp['image_url']; ?>"></div>
								<a href="remove.php?<?php echo http_build_query(['image_url' => $stamp['image_url']]); ?>"><button><?php l($s['remove']); ?></button></a>
							</div><?php
						}
						mysql_close();
					?>
				</div>
			</div>
			<div style="text-align: center; "><?php
				$collection = $twitter->get('collections/entries', ['id' => 'custom-'.COLLECTON_STAMP, 'count' => '200']);
				$i = 0;
				foreach($collection->response->timeline as $context){
					$status = $collection->objects->tweets->{$context->tweet->id};
					?>
					<div style="display: inline-block; width: 240px; ">
						<div id="stamp-<?php echo $i; ?>"></div>
						<a href="add.php?<?php echo http_build_query(['image_url' => $status->entities->media[0]->media_url_https]); ?>"><button><?php l($s['add']); ?></button></a><br>
						<br>
					</div>
					<script>$(function(){
						twttr.widgets.createTweet('<?php echo $status->id; ?>', document.getElementById('stamp-<?php echo $i; ?>'));
					});</script>
					<?php
					$i++;
				}
			?></div>
		</div>
	</body>
</html>
