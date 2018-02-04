<?php
        include('/var/www/twiverse.php');
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
		<title>NFC Battler</title>
	</head>
	<?php head(); ?>
	<body>
		<div class="paddingleft paddingright">
			<span style="float: right; text-align: right; ">
				Ver: 1.00<br>
				Developed by <a href="https://twitter.com/Twiverse_admin">@Twiverse_admin</a><br>
			</span>
			<h1 style="text-align: center; ">NFC Battler</h1>
		</div>
		<table style="width: 100%; "><tr>
			<td style="vertical-align: top; ">
				<div class="marginleft marginright">
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
				<div id="nfcbattler" style="position: relative; height: 128px; text-align: center; "></div>
			</td>
			<td style="width: 320px; ">
				<a class="twitter-timeline" href="https://twitter.com/hashtag/NFCBattler" data-widget-id="948739919609970690">#NFCBattler のツイート</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</td>
		</tr></table>
	</body>
	<script>
		$(function(){
			$(window).on('AmiiboTagSearchStart', function(e){
                        });
                        $(window).on('AmiiboTagSearchCancel', function(e){
                        });
                        $(window).on('AmiiboTagDetected', function(e){
                                var tag = customEvent.tag;
                                if (tag.isRead) amiibo.playAmiiboSE();
				var pts = 1;
				tag.info.id.forEach(function(id, i){
					pts *= (id<<i) + 1;
				});

				var pts_str = String(pts).replace(/(\d)(?=(\d\d\d\d)+(?!\d))/g, '$1,');
				$('#nfcbattler').html('<div id="pts-block" style="position: relative; display: inline-block; "><h2>このタグの戦闘力は...</h2><h1><span id="pts" style="display: none; ">'+pts_str+'!!!<span></h1></div>');
				setTimeout(function(){
					$('#pts').show();
					setTimeout(function(){
						//$('#pts-block').animate({top: '-32px'}, 1000, 'swing', function(){
							$('#nfcbattler').append('<table class="fadeIn animated" style="position: relative; top: 0px; width: 100%; text-align: center; "><tr><td><a href="https://twitter.com/share?ref_src=twsrc%5Etfw" class="twitter-share-button" data-size="large" data-text="戦闘力「'+pts_str+'」のNFCタグを発見！(Ver: 1.00 )" data-hashtags="NFCBattler" data-show-count="false">Tweet</a>Twitterで共有しよう！</td><td><button onclick="window.location.reload(); ">もういっかい！</button></td></tr></table>');
							$('#nfcbattler').append('<script async src="https://platform.twitter.com/widgets.js" charset="utf-8">');
						//});
					}, 1000);
				}, 1000);
                        });
                        $(window).on('AmiiboTagLost', function(e){
                                //amiibo.endSearchAmiibo();
                                //amiibo.startSearchAmiibo();
                        });
			amiibo.startSearchAmiibo();

			$('#nfcbattler').html('<h2 id="wait" class="fadeIn animated" style="animation-delay: 1s; "><br><br>NFCタグたいきちゅう<span id="wait-leader"></span></h2>');
			/*var wait_cnt = 0;
			setInterval(function(){
				switch((wait_cnt++)%2){
					case 0:
					$('#wait').attr('class', 'bounce animated');
					$('#wait').attr('style', '');
					break;
					case 1:
					$('#wait').attr('class', '');
					$('#wait').attr('style', '');
					break;
				}
			}, 10000);*/
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
		});
	</script>
	<?php if (useragent() != 'wiiu'){ ?>
		<script>
			$(window).load(function(){
				alert('このプログラムはWii Uインターネットブラウザより動作します。\nWii Uよりアクセスしてみてください。\n\nThis program supports Wii U Internet Browser only. \nPlease try to access from Wii U. ');
			});
		</script>
	<?php } ?>
</html>
