<?php
	include('/var/www/twiverse.php');
	$s = [
		'title' => ['ja' => "コミュニティ", 'en' => "Communities"],
		//'' => ['ja' => "", 'en' => ""],
		//'' => ['ja' => "", 'en' => ""],
	];

	mysql_start();
	$res = mysql_query("select * from comm order by name");
	$comm_list = [];
	while($row = mysql_fetch_assoc($res)) array_push($comm_list, $row);
	mysql_close();
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
				/*.banner{
					float: right;
					max-height: 7em;
					border-radius-upright: 0.25em;
					border-radius-bottomright: 0.25em;
					border-left: 1px solid lightgray;
				}*/
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
		<div class="topbar"><?php l($s['title']); ?></div>
		<div class="main">
			<div lang="ja" class="header">
				現在、<?php echo count($comm_list); ?>個のコミュニティが設立されています。<br>
                        	Wii U、3DS、PS VITAの端末から、スクリーンショット付きの投稿をしてコミュニティを設立できます。<br>
				<a href="<?php echo ROOT_URL; ?>view/report/">コミュニティ設立・変更依頼フォーム</a><br>
				コミュニティに関するご報告、バナー画像については<a href="https://twitter.com/Twiverse_admin">@Twiverse_admin</a>まで。<br>
				<br>
			</div>
			<!--<div lang="en" class="header">
				<?php echo count($comm_list); ?> communities are established now. <br>
				<a href="estcomm.php">How do I establish more communities?</a><br>
				To change or report the community name, or ask about banner image, please contact to <a href="https://twitter.com/Twiverse_admin">@Twiverse_admin</a>. <br>
				<br>
			</div>-->
			<div style="text-align: center; ">
				<?php
					mysql_start();
					$i = 0;
					foreach($comm_list as $comm){?>
						<a href="<?php echo ROOT_URL; ?>view/?comm_id=<?php echo $comm['id']; ?>" style="text-decoration: none; color: inherit; "><div class="card comm" style="text-align: left; ">
							<?php if ($comm['banner']) echo '<div class="banner-wrapper"><img src="'.$comm[banner].'" class="banner"></div>'; ?>
							<div class="card-article">
								<h3 class="underline" style="margin: 0; font-size: medium; word-wrap: break-word; "><?php echo $comm['name']; ?></h3>
								<p class="disabled" style="font-size: small; "><?php
									$detector = detector($comm['soft_id']);
									echo $detector['name'].' ';
									if ($comm['post_n']) echo '投稿 '.$comm['post_n'].' ';
									if ($comm['list_n']) echo 'リスト '.$comm['list_n'].' ';
								?></p>
							</div>
						</div></a>
					<?php }
					mysql_close();
				?>
			</div>
		</div>
	</body>
</html>
