<?php
	include('/var/www/twiverse.php');
	$s = [
		'title' => ['ja' => 'お絵かき投稿スプリッター', 'en' => 'The drawing posts splitter', ],
		'desc' => [
			'ja' => 'スクリーンショット付きお絵かき投稿を、「スクリーンショット」と「お絵かき」に分割するツールです。',
			'en' => 'By this tool, the drawing posts with screenshot can be splitted into drawing and screenshot. ',
		],
		'image_url' => [
			'ja' => '画像のURL',
			'en' => 'The URL of the image',
		],
		'or' => ['ja' => 'もしくは', 'en' => 'or', ],
		'run' => ['ja' => '実行', 'en' => 'Run', ],
		'screenshot' => ['ja' => 'スクリーンショット', 'en' => 'Screenshot', ],
		'drawing' => ['ja' => 'お絵かき', 'en' => 'Drawing', ],
		'err_notfound' => ['ja' => '画像を選択してください。', 'en' => 'Please set the image. ', ],
		//'' => ['ja' => '', 'en' => '', ],
	];
?>

<!DOCTYPE html>
<html lang = "ja">
	<?php head(); ?>
	<body>
		<h2 class="topbar"><?php l($s['title']); ?></h2>
		<div class="main paddingleft paddingright">
			<br>
			<?php l($s['desc']); ?><br>
			<br>
			<form action="#" method="post" enctype="multipart/form-data">
				<input name="selimg" type="text" placeholder="<?php l($s['image_url']); ?>" style="width: 100%; "><br>
				<?php l($s['or']); ?><br>
				<input name="selimg-data" type="file" accept="image/jpeg"><br>
				<br>
				<input type="submit" value="<?php l($s['run']); ?>">
			</form>
			<br>
			<?php
				try{
					if ($_FILES['selimg-data']['name'] != ''){
						$img_path = $_FILES['selimg-data']['tmp_name'];
					}else{
						$img_path = tempnam('/tmp', 'php').'.jpg';
						$img_data = file_get_contents($_POST['selimg']);
						if ($img_data === false) throw new Exception(s($s['err_notfound']));
						file_put_contents($img_path, $img_data);
						exec('sync');
					}

					list($width, $height) = getimagesize($img_path);
					$draw_height = (int)($width*120.0/300.0);

					$ss_path = tempnam('/tmp', 'php').'.jpg';
					exec('convert '.$img_path.' -crop '.$width.'x'.($height-$draw_height).'+0+0 '.$ss_path);
					$draw_path = tempnam('/tmp', 'php').'.jpg';
					exec('convert '.$img_path.' -crop '.$width.'x'.$draw_height.'+0+'.($height-$draw_height).' '.$draw_path);
					exec('sync');
					?><?php l($s['screenshot']); ?><br><img src="data:image/jpeg;base64,<?php echo base64_encode(file_get_contents($ss_path)); ?>" style="max-width: 100%; "><br>
					<?php l($s['drawing']); ?><br><img src="data:image/jpeg;base64,<?php echo base64_encode(file_get_contents($draw_path)); ?>" style="max-width: 100%; "><br><?php
					unlink($img_path);
					unlink($ss_path);
					unlink($draw_path);
				}catch (Exception $e){
					echo $e->getMessage();
				}
			?>
		</div>
	</body>
</html>
