<?php
	include('/var/www/twiverse.php');
	$s = [
		'title' => ['ja' => "コミュニティ", 'en' => "Communities"],
		'detector' => ['ja' => "ディテクター", 'en' => "detector"],
		//'' => ['ja' => "", 'en' => ""],
	];
	if (isset($_GET['detector'])) $detector_prefix = mysql_escape_string($_GET['detector']);

	function helpbutton($desc){ ?>
		<img src="<?php echo ROOT_URL; ?>img/help.png" style="width: 1em; cursor: pointer; " onclick="alert('<?php echo $desc; ?>'); ">
	<?php }
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset = "UTF-8">
		<style type="text/css">
			.comm{
				display: inline-block;
				max-width: 320px;
			}
			.banner{
				border-radius-upright: 0.25em;
				border-radius-upleft: 0.25em;
				max-width: 100%;
				max-height: 200px;
			}
			.banner-wrapper{
				background-color: whitesmoke;
				text-align: center;
			}
			@media screen and (min-width: 768px) {
				.comm{
					width: 45%;
				}
			}
			@media screen and (max-width: 767px) {
				.comm{
					width: 90%;
				}
			}
		</style>
	</head>
	<?php head(); ?>
	<body>
		<div class="topbar"><?php
			if (isset($detector_prefix)){
			}
			l($s['title']);
		?></div>
		<div class="main">
			<div lang="ja" class="header">
				<a class="linkbutton" href="../makecomm/">コミュニティを設立する</a>
				ディテクターとは<?php helpbutton('添付画像を自動認識し、コミュニティに振り分けるプログラムです。\nたとえば、3DS ディテクターは 3DS のスクリーンショットを認識し、3DS ディテクター内のコミュニティに振り分けます。'); ?>
				<br>
			</div>
			<div style="text-align: center; ">
				<?php try{
					mysql_start();
					if (isset($detector_prefix)){
					        $comms = mysql_throw(mysql_query("select * from comm where soft_id like '".$detector_prefix."%' order by name"));
						while($comm = mysql_fetch_assoc($comms)){ ?>
						        <a href="<?php echo ROOT_URL; ?>view/?comm_id=<?php echo $comm['id']; ?>" style="text-decoration: none; color: inherit; "><div class="card comm" style="text-align: left; ">
						                <?php if ($comm['banner']) echo '<div class="banner-wrapper"><img src="'.$comm[banner].'" class="banner"></div>'; ?>
						        <div class="card-article">
						                        <h3 class="underline" style="margin: 0; font-size: medium; word-wrap: break-word; "><?php echo $comm['name']; ?></h3>
						                        <p class="disabled" style="font-size: small; "><?php
						                                //$detector = detector($comm['soft_id']);
						                                //echo $detector['name'].' ';
						                                if ($comm['post_n']) echo '投稿 '.$comm['post_n'].' ';
						                                if ($comm['list_n']) echo 'リスト '.$comm['list_n'].' ';
						                        ?></p>
						                </div>
						        </div></a>
						<?php }
					}else{
						$detectors = mysql_throw(mysql_query("select * from detector order by name"));
						while($detector = mysql_fetch_assoc($detectors)){
							$comm_count = mysql_num_rows(mysql_throw(mysql_query("select * from comm where soft_id like '".$detector['prefix']."%'")));
							?>
							<a href="<?php echo ROOT_URL; ?>view/search/?detector=<?php echo $detector['prefix']; ?>" class="a-disabled"><div class="card comm" style="text-align: left; "><div class="card-article">
								<h3 class="underline" style="margin: 0; font-size: medium; word-wrap: break-word; "><?php echo $detector['name']; ?> <?php l($s['detector']); ?></h3>
								<p class="disabled" style="font-size: small; ">
									コミュニティ <?php echo $comm_count; ?><br>
									<?php echo nl2br(htmlspecialchars($detector['description'])); ?>
								</p>
							</div></div></a>
						<?php }
					}
					mysql_close();
				}catch(Exception $e){ catch_default($e); }?>
			</div>
		</div>
	</body>
</html>
