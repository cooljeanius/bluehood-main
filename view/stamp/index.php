<?php
	include('/var/www/twiverse.php');
	unset($_SESSION['collection_cursor']);
	$twitter = twitter_start();	// 手もちスタンプの管理のため、ログイン必要

	$s = [
		'title' => ['ja' => "スタンプ", 'en' => "Stamp", ],
		'make' => ['ja' => "スタンプを作る", 'en' => "Create a stamp", ],
		'selected' => ['ja' => "手持ちのスタンプ", 'en' => "Selected stamps", ],
		'add' => ['ja' => "追加", 'en' => "Add", ],
		'dl' => ['ja' => "3D ダウンロード", 'en' => "3D Download", ],
		'remove' => ['ja' => "削除", 'en' => "Remove", ],
		'help_3d' => ['ja' => "3D ダウンロードとは? ", 'en' => "What's 3D Download? ", ],
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
			<div class="header" style="position: relative; ">
				<a href="<?php echo ROOT_URL; ?>tweet/stamp/" class="linkbutton" style="position: absolute; top: 0; right: 0; "><?php l($s['make']); ?></a>
				作ったスタンプをお絵かき投稿で使用できます。
			</div>
			<div class="header">
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
			<div class="paddingleft paddingright">
				<span style="font-size: small; "><?php l($s['help_3d']); ?></span><?php helpbutton('スタンプの 3D モデルを stl ファイルでダウンロードできます。\n3D プリントすれば、現実世界で使えるかも? '); ?>
			</div>
			<div style="text-align: center; "><?php
				$request = ['id' => 'custom-'.COLLECTON_STAMP, 'count' => '200'];
				if (isset($_GET['i'])) $request['max_position'] = $_GET['i'];
				$collection = $twitter->get('collections/entries', $request);
				$i = 0;
				foreach($collection->response->timeline as $context){
					$status = $collection->objects->tweets->{$context->tweet->id};
					?>
					<div style="display: inline-block; width: 240px; ">
						<div id="stamp-<?php echo $i; ?>"></div>
						<a href="add.php?<?php echo http_build_query(['image_url' => $status->entities->media[0]->media_url_https]); ?>"><button><?php l($s['add']); ?></button></a>
						<a href="stlstamp/?<?php echo http_build_query(['stamp' => basename($status->entities->media[0]->media_url_https)]); ?>"><button><?php l($s['dl']); ?></button></a><br>
						<br>
					</div>
					<script>$(function(){
						twttr.widgets.createTweet('<?php echo $status->id; ?>', document.getElementById('stamp-<?php echo $i; ?>'));
					});</script>
					<?php
					if (++$i >= MAX_TWEETS){
						?><br><a href="?<?php echo http_build_query(['i' => $context->tweet->sort_index]); ?>"><button>もっとみる</button></a><?php
						break;
					}
				}
			?></div>
		</div>
	</body>
</html>
