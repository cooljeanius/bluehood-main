<?php
	include('/var/www/twiverse.php');
	$s = [
		'title' => ['ja' => 'ツールボックス', 'en' => 'Tools', ],
		'desc' => [
			'ja' => '2018/01/21　いままで開発してきた様々な小コンテンツを集約しました。',
			'en' => '2018/01/21　This page collects some little contents developed by @Twiverse_admin. ',
		],
		'splitter' => ['ja' => 'お絵かき投稿スプリッター', 'en' => 'The drawing posts splitter', ],
		'splitter_desc' => [
			'ja' => 'スクリーンショット付きお絵かき投稿を、「スクリーンショット」と「お絵かき」に分割するツールです。
				Wii U・PC・スマホ等で描いたお絵かきが対象です。
				ゲームメモからのお絵かきには未対応です。',
			'en' => 'A tool to split drawing posts with screenshot into screenshot and drawing. 
				This supports drawing posts by Wii U, PC, smartphone, etc. only, not Game Notes on 3DS. ',
		],
		'nfcbattler' => ['ja' => 'NFC Battler', 'en' => 'NFC Battler', ],
		'nfcbattler_desc' => [
			'ja' => 'あなたが持ってるNFCタグ(amiiboやSuicaなど)の戦闘力を測定します。
				測定にはWii Uが必要です。',
			'en' => 'Your NFC Tags (amiibo, etc.) can be measured for their own combat power by this tool. 
				Wii U Internet Browser only. ',
		],
		'filter' => ['ja' => 'コミカルフィルター', 'en' => 'The filter for Game Notes', ],
		'filter_desc' => [
			'ja' => '3DSのゲームメモで描いたお絵かきがコミック風になるフィルターです。
				赤色と青色にトーンが付き、白黒になります。
				お絵かき投稿の一部に組み込まれてますので、まずはゲームメモの画像ファイルを選んでください。',
			'en' => 'A image filter for 3DS Game Notes, it may make your Game Notes more clear. 
				Red and blue areas are replaced with tones, and your drawings change to look like a comic. 
				This tool is included on the drawing post page, so please select your Game Notes image file first. ',
		],
		'album' => ['ja' => 'アルバム', 'en' => 'Album', ],
		'album_desc' => [
			'ja' => 'スクリーンショットをアルバムに登録して、投稿に使用することができます。',
			'en' => "By registering to BlueHood Album, your screenshots can be used to posts anytime. 
				This doesn't support old 3DS now, sorry! ",
		],
		'stamp' => ['ja' => 'スタンプ', 'en' => 'Stamp', ],
		'stamp_desc' => [
			'ja' => 'Wii U・PC・スマホ等からのお絵かきで使用できるスタンプを作成できます。
				しかし、スタンプ機能は動作がまだ安定していません。',
			'en' => "The stamp creator for drawing tool on Wii U, PC, smartphone, etc. 
				But, it doesn't work comfortably",
		],
		//'' => ['ja' => , 'en' => '', ],
	];
?>

<!DOCTYPE html>
<html>
	<head>
		<style>
			.tool{
				vertical-align: top;
			}
			.tool-desc{
				text-align: left;
				font-size: small;
			}
			@media screen and (min-width: 766px){
				.tool{
					display: inline-block;
					width: 35%;
				}
			}
                        @media screen and (max-width: 765px){
					width: 90%;
			}
		</style>
	</head>
	<?php head(); ?>
	<body>
		<h2 class="topbar"><?php l($s['title']); ?></h2>
		<div class="main">
			<div class="header">
				<?php l($s['desc']); ?><br>
			</div>
			<div style="text-align: center; ">
				<!--<a class="a-disabled" href="splitter.php"><div class="card tool">
					<h3><?php l($s['splitter']); ?></h3>
					<div class="card-article tool-desc"><?php l($s['splitter_desc']); ?></div>
				</div></a>-->
				<a class="a-disabled" href="nfcbattler/"><div class="card tool">
					<h3><?php l($s['nfcbattler']); ?></h3>
					<div class="card-article tool-desc"><?php l($s['nfcbattler_desc']); ?></div>
				</div></a>
				<a class="a-disabled" href="<?php echo ROOT_URL; ?>tweet/draw/gamememo/"><div class="card tool">
					<h3><?php l($s['filter']); ?></h3>
					<div class="card-article tool-desc"><?php l($s['filter_desc']); ?></div>
				</div></a>
				<a class="a-disabled" href="<?php echo ROOT_URL; ?>user/album"><div class="card tool">
					<h3><?php l($s['album']); ?></h3>
					<div class="card-article tool-desc"><?php l($s['album_desc']); ?></div>
				</div></a>
				<a class="a-disabled" href="<?php echo ROOT_URL; ?>view/stamp/"><div class="card tool">
					<h3><?php l($s['stamp']); ?></h3>
					<div class="card-article tool-desc"><?php l($s['stamp_desc']); ?></div>
				</div></a>
				<a class="a-disabled" href="https://docs.google.com/forms/d/e/1FAIpQLScAqd6AVvozFzSYptt9KD2B2nGWXx3webKa1i_665eKpCrc5w/viewform"><div class="card tool">
					<h3>BlueHood スタンプ申込フォーム</h3>
					<div class="card-article tool-desc">3D プリントした BlueHood スタンプを郵送いたします。
					私の趣味程度のため、無料、1 週間に 1 件程度の対応となります。</div>
				</div></a>
				<!--<a class="a-disabled" href="3dpost"><div class="card tool">
					<h3>3D POST</h3>
					<div class="card-article tool-desc">3D データを投函するサイト。
					まだ、開発者 (@bluehood_admin) にしか送れないけどね。</div>
				</div></a>-->
				<a class="a-disabled" href="troopa"><div class="card tool">
					<h3>Troopa 👀</h3>
					<div class="card-article tool-desc">Web 上で動く Toy シンセサイザー。
					PC 限定コンテンツです。</div>
				</div></a>
			</div>
		</div>
	</body>
</html>
