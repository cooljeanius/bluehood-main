<?php
        include('/var/www/twiverse.php');
	$ver = (int)2;

	if ($_GET['img']){
		header('Content-Type: image/png');
		die(file_get_contents('test.png'));
	}
?>
<html>
	<head>
		<meta charset=utf-8>
		<link type="text/css" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
		<style>
			#translate, .sidemenu{
				display: none !important;
			}
		</style>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jsSHA/1.6.0/sha256.js"></script>

                <meta name="twitter:card" content="summary" />
                <meta name="twitter:site" content="@Twiverse_admin" />
                <meta name="twitter:title" content="NFC Battler" />
                <meta name="twitter:description" content="あなたが持っているNFCタグの戦闘力を測定いたします。" />
                <!--<meta name="twitter:image" content="<?php echo 'https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']; ?>&img=1" />-->
		<title>NFC Battler</title>
	</head>
	<?php head(); ?>
	<body>
		<div class="paddingleft paddingright">
			<span style="float: right; text-align: right; ">
				Ver: <?php echo $ver; ?>　<a href="1.00.php">Old version</a><br>
				Developed by <a href="https://twitter.com/Twiverse_admin">@Twiverse_admin</a><br>
			</span>
			<h1 style="text-align: center; ">NFC Battler</h1>
		</div>
		<table style="width: 100%; "><tr>
			<td style="vertical-align: top; ">
				<div id="nfcbattler" style="position: relative; text-align: center; ">
				<div class="marginleft marginright" style="text-align: left; ">
					<p lang="ja">あなたが持っているNFCタグを、Wii UのNFCリーダー（GamePad の左下）にかざしてみよう！<br>
					NFCタグは、amiiboやSuicaなどの交通系マネーに搭載されています。</p>
					<p lang="en">Let's try holding up your NFC tags! <br>
					The NFC tags are built in amiibos, electronic money and so on. </p>
					<p lang="ja">※免責事項<br>
					NFCタグの読み取りのみを行い、書き込みは行いません。<br>
					このプログラムを使用したことによる責任は負いかねます。Sorry！</p>
					<p lang="en">※Disclaimer<br>
					The NFC tags are only read, not written. <br>
					This program doesn't come with warranty. Sorry! </p>
				</div>
				</div>
			</td>
			<td style="width: 320px; vertical-align: top; ">
				<a class="twitter-timeline" href="https://twitter.com/hashtag/NFCBattler" data-widget-id="948739919609970690">#NFCBattler のツイート</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</td>
		</tr></table>
	</body>
	<script>
		$(function(){
			var measure = function(sha256, isshare){
				var sha256_work = sha256;
				var rnds = [];
				while(sha256_work != ''){
					rnds.push(parseInt(sha256_work.slice(0, 4), 16));
					sha256_work = sha256_work.slice(4);
				}

				var hp = rnds[0]%2001;
				var mp = rnds[1]%2001;
				var power = rnds[2]%2001;
				var speed = rnds[3]%2001;
				var protect = rnds[4]%2001;
				var pts = hp + mp + power + speed + protect;

				scrollTo(0, 0);
				$('#nfcbattler').html('<div id="pts-block" style="position: relative; "><h2 style="display: inline; ">このタグの戦闘力は...</h2><h1 style="display: inline; "><span id="pts" style="display: none; ">'+String(pts)+'!!!<span></h1></div>');
				setTimeout(function(){
					$('#pts').show();
					setTimeout(function(){
						$('#nfcbattler').append('\n\
							<div id="result" class="fadeIn animated">\n\
								<br>\n\
								<canvas id="chart"></canvas>\n\
								<br>\n\
							</div>\n\
						');
						var url = '<?php echo dirname('https://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']); ?>/';
						if (isshare){
							var share_text = '戦闘力「'+String(pts)+'」のNFCタグを発見！(Ver: <?php echo $ver; ?>)\n・たいりょく '+hp+'\n・こうげき '+power+'\n・すばやさ '+speed+'\n・ぼうぎょ '+protect+'\n・とくぎ '+mp+'\n';
							url += '<?php echo $ver; ?>.php?id='+sha256;
							$('#result').append('\n\
								<table style="position: relative; top: 0px; width: 100%; text-align: center; "><tr><td><a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-size="large" data-text="'+share_text+'" data-hashtags="NFCBattler" data-show-count="false" data-url="'+url+'" data-related="Twiverse_admin">Tweet</a>Twitterで共有しよう！</td><td><button onclick="window.location.reload(); ">もういっかい！</button></td></tr></table>\n\
								<br>\n\
								<textarea rows="9" style="width: 80%; ">'+share_text+url+'</textarea>\n\
								<script async src="https://platform.twitter.com/widgets.js" charset="utf-8">\n\
							');
						}else{
							$('#result').append('\n\
								<a href="'+url+'"><button>あなたもやってみよう！</button></a><br>\n\
								※Wii Uインターネットブラウザが必要です。<br>\n\
							');
						}

						var chart_color = 'hsl('+(pts%360)+', 100%, 50%)';
						var chart_color_trans = 'hsla('+(pts%360)+', 100%, 50%, 0.5)';
						var ctx = document.getElementById('chart').getContext('2d');
						var chart = new Chart(ctx, {
							type: 'radar',
							data: {
								labels: ['たいりょく '+hp, 'こうげき '+power, 'すばやさ '+speed, 'ぼうぎょ '+protect, 'とくぎ '+mp],
								datasets: [{
									backgroundColor: chart_color_trans,
									borderColor: chart_color,
									pointBackgroundColor: chart_color,
									data: [hp, power, speed, protect, mp],
								}],
							},
							options: {
								title: {
									display: false,
									text: 'ステイタス',
								},
								legend: {
									display: false,
								},
								scale: {
									ticks: {
										display: false,
										min: 0,
										max: 2000,
									},
								},
							},
						});
					}, 1000);
				}, 1000);
			};

			<?php if (isset($_GET['id'])){ ?>
				measure('<?php echo $_GET['id']; ?>', false);
			<?php }else{
				if (useragent() == 'wiiu'){ ?>
					$(window).on('AmiiboTagSearchStart', function(e){
	                	        });
	                	        $(window).on('AmiiboTagSearchCancel', function(e){
	                	        });
	                	        $(window).on('AmiiboTagDetected', function(e){
	                	                var tag = customEvent.tag;
	                	                if (tag.isRead) amiibo.playAmiiboSE();

						var id_hex = '';
						tag.info.id.forEach(function(id, i){
							id_hex += ('0'+id.toString(16)).slice(-2);
						});
						measure(new jsSHA(id_hex, 'HEX').getHash('SHA-256', 'HEX'), true);
	        	                });
	        	                $(window).on('AmiiboTagLost', function(e){
	        	                        //amiibo.endSearchAmiibo();
	        	                        //amiibo.startSearchAmiibo();
		                        });
					amiibo.startSearchAmiibo();

					$('#nfcbattler').append('<h2 id="wait" class="fadeIn animated" style="animation-delay: 1s; "><br><br>NFCタグたいきちゅう<span id="wait-leader"></span></h2>');
					var wait_cnt2 = 0;
					setInterval(function(){
						switch((wait_cnt2++)%4){
							case 0:
							$('#wait-leader').html('');
							break;
							case 1:
							$('#wait-leader').html('.');
							break;
							case 2:
							$('#wait-leader').html('..');
							break;
							case 3:
							$('#wait-leader').html('...');
							break;
						}
					}, 500);
				<?php }else{ ?>
					$('#nfcbattler').append('\n\
						<p lang="ja" class="paddingleft paddingright" style="text-align: left; ">このプログラムはWii Uインターネットブラウザより動作します。<br>Wii Uよりアクセスしてみてください。</p>\n\
						<p lang="en" class="paddingleft paddingright" style="text-align: left; ">This program supports Wii U Internet Browser only. <br>Please try accessing from Wii U. </p>\n\
					');
				<?php }
			} ?>
		});
	</script>
</html>
