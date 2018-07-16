<?php
	include('/var/www/twiverse.php');
	$s = [
		'title' => ['ja' => 'BlueHood利用ガイド', 'en' => "BlueHood User's Guide"],
		'desc' => [
			'ja' => 'BlueHoodを安全に利用するために、このガイドを参考にしてください。<br>
			なお、このガイドはTwitter利用に関する制限を規定するものではありません。<br>
			※このサイトは、日本時間の夜になると BanWolf になります。',
			'en' => "Please refer this guide to use BlueHood more safely. <br>
                	But, this doesn't regulate your Twitter rules. <br>
			※This site will change to \"BanWolf\" in evening (Japanese Time). ",
		],
		'responsibility' => ['ja' => '免責事項', 'en' => 'Responsibility'],
		'res_desc' => [
			'ja' => 'BlueHoodは個人によって現在も開発中であり、不安定な要素を含みます。<br>
                        無保証で使用してくださるようお願いします。',
			'en' => 'BlueHood is now under development, so it has no warranty. ',
		],
		'env' => ['ja' => '推奨環境', 'en' => 'Enviroment Requirements'],
		'env_desc' => [
			'ja' => 'PC・タブレット・スマホからは、Chromeでのアクセスを推奨します。<br>
                        3DSからのお絵かき投稿はゲームメモを使用します。',
			'en' => "Chrome browser is recommended to access from PC, tablet, and smartphone. <br>
			This site shows Wii U version pages for PC, and 3DS version pages for smartphone. <br>
			When posting a drawing from 3DS, you'll need to use the Game Notes application. ",
		],
		'about' => ['ja' => 'BlueHoodはTwitterを通じて参加するサービス', 'en' => 'BlueHood, a service through Twitter'],
		'about_desc' => [
			'ja' => 'BlueHoodにはさまざまなコミュニティがあり、コミュニティはツイートによって成り立っています。<br>
                        よって、BlueHoodを通して投稿した内容は、Twitterの世界のたくさんの人にも発信されます。<br>
                        さらに、ツイートはインターネット上に公開されます。<br>
                        BlueHoodのコミュニティ内でしか発信されないと思ってしまうかもしれませんが、インターネット上の誰でも見られることを注意してください。',
			'en' => 'BlueHood is made up of some communities, and they are made up of tweets. <br>
                        So, your posts from BlueHood are released on Twitter. <br>
                        And tweets are released on internet. <br>
                        You may think your posts are released on only BlueHood, but your posts are shown to everyone. ',
		],
		'rule' => ['ja' => 'Twitterルールを守る', 'en' => 'Follow Twitter rules'],
		'rule_desc' => [
			'ja' => 'BlueHoodを利用するには、Twitterのルールを守る必要があります。<br>
                        ルールでは、いやがらせや個人情報についても明記されています(2017年9月13日現在)。<br>
                        くわしくは、<a href="https://support.twitter.com/articles/253501" target="_top">Twitterルール</a>を確認してください。',
			'en' => 'To use BlueHood, you need to follow Twitter rules. <br>
                        They mention also harassment and personal information. <br>
                        For more information, please refer <a href="https://support.twitter.com/articles/253501" target="_top">Twitter rules</a>. '
		],
		'violation' => ['ja' => '違反投稿かな？と思ったら', 'en' => 'Report violations to Twitter'],
		'violation_desc' => [
			'ja' => 'BlueHoodは、ツイートに対する管理権および義務を有しません。<br>
			また、このガイドはTwitter利用に関する制限を規定するものではありません。<br>
			違反投稿と思われるツイートを見つけたら、<a href="https://support.twitter.com/articles/253501" target="_top">Twitterルール</a>と照らし合わせ、<a href="https://support.twitter.com/articles/486421">Twitterへ報告</a>してください。',
			'en' => 'BlueHood doesn\'t have rights and responsibilities for tweets posted thru it. <br>
			And this guide doesn\'t regulate your Twitter rules. <br>
			When you encounter a violation, please check with <a href="https://support.twitter.com/articles/253501" target="_top">Twitter rules</a>, and <a href="https://support.twitter.com/articles/486421">Report to Twitter</a>. ',
		],
		'note' => ['ja' => '通知について', 'en' => 'About BlueHood notification'],
		'note_desc' => ['ja' => '<img src="'.ROOT_URL.'img/feed.png" alt="通知" style="width: 1em; ">アイコンによる通知は、次のように機能します。
                        <ul>
                                <li>ページ移動時に通知を確認します。</li>
                                <li>BlueHoodからの投稿に対するお気に入りおよびリツイートを確認します。</li>
                                <li>通知があるとき、お気に入りおよびリツイートの件数を表示します。</li>
                        </ul>
                        通知機能の動作は無保証とします。',
			'en' => 'BlueHood notification (<img src="'.ROOT_URL.'img/feed.png" alt="notification" style="width: 1em; "> icon) works through follow rules.
                        <ul>
                                <li>Check favorites and retweets when move pages. </li>
                                <li>Show the count of favorites and retweets. </li>
                        </ul>
                        BlueHood notification is especially no warranty. ',
		],
		//'' => ['ja' => '', 'en' => ''],
	];
?>

<!DOCTYPE html>
<html>
	<?php head(); ?>
	<head>
		<style type="text/css">
			.card{
				text-align: center;
			}
			.card p{
				text-align: left;
				line-height: 1.5;
			}
			.card b{
				color: #55acee;
			}
			h3{
				color: orange;
				border-bottom: 2px solid gold;
				margin-top: 0.5em;
				/*font-size: medium;*/
				text-align: left;
			}
		</style>
		<script>
			$(function(){
				$('.section').hide();
				$('#section-1').show();

				$('#menu').change(function(){
					$('.section').hide();
					$('#section-'+$('#menu').val()).show();
				});
			});
		</script>
	</head>
	<body>
		<h2 class="topbar"><?php l($s['title']); ?></h2>
		<div class="main paddingleft paddingright" style="padding-top: 1em; ">
			<select id="menu">
				<option value="1">1. BlueHood とは?</option>
				<option value="2">2. 準備しよう</option>
				<option value="3">3. コンテンツの紹介</option>
				<option value="4">4. BlueHood のルール</option>
			</select><br>
			<br>

			<div id="section-1" class="section">

				<div class="card card-article">
					<table><tr>
					<td><img src="<?php echo ROOT_URL; ?>img/twiverse/default.png" style="width: 64px; "></td>
					<td><img src="<?php echo ROOT_URL; ?>img/banner/default.png"></td>
					</tr></table>
					<table><tr>
					<td><img src="<?php echo ROOT_URL; ?>img/twiverse/banwolf.png" style="width: 64px; "></td>
					<td><img src="<?php echo ROOT_URL; ?>img/banner/banwolf.png"></td>
					</tr></table>
					<p><?php l($s['desc']); ?></p>
				</div>

				<div class="card card-article">
					<h3><?php l($s['responsibility']); ?></h3>
					<p><?php l($s['res_desc']); ?></p>
				</div>

				<div class="card card-article">
					<h3>BlueHood とは?</h3>
					<p>BlueHood は、<b>Twitter のイメージをつなげるコミュニティ</b>です。<br>
					Twitter をベースとして、BlueHood のコミュニティが構成されています。<br>
					BlueHood からツイートする画像 (イメージ) は、<b>自動認識</b>によってコミュニティに登録されます。<br>
					たとえば、ネコの画像をツイートしようとすると、AI による画像認識によって「Cat」コミュニティに登録されるかもしれません。<br>
					<br>
					また、写真だけでなく白黒の<b>お絵かき</b>をツイートすることができます。<br>
					さらに、お絵かきで使用できる<b>スタンプ</b>を制作することもできます。<br>
					Twitter の世界に、もっと「イメージのつながり」を作りましょう！
</p>
				</div>
			</div>

			<div id="section-2" class="section">
				<div class="card card-article">
					<h3><?php l($s['env']); ?></h3>
					<p><?php l($s['env_desc']); ?></p>
				</div>
			</div>

			<div id="section-4" class="section">
				<span id="service"></span>
				<div class="card card-article">
				<h3><?php l($s['about']); ?></h3>
				<p><?php l($s['about_desc']); ?></p>
				<img src="<?php echo ROOT_URL; ?>img/TwitterLogo.png">
				</div>

				<span id="rules"></span>
				<div class="card card-article">
				<h3><?php l($s['rule']); ?></h3>
				<p><?php l($s['rule_desc']); ?></p>
				<br>
				<br>
				<figure>
					<img src="<?php echo ROOT_URL; ?>img/twiverse/default.png" width="160px">
					<figcaption>BlueHood</figcaption>
				</figure>
				<br>
				<br>
				</div>

				<span id="violation"></span>
				<div class="card card-article">
				<h3><?php l($s['violation']); ?></h3>
				<p><?php l($s['violation_desc']); ?></p>
				<br>
				<br>
				<figure>
					<img src="<?php echo ROOT_URL; ?>img/twiverse/banwolf.png" width="160px">
					<figcaption>BanWolf</figcaption>
				</figure>
				<br>
				<br>
				</div>
			</div>

			<center>本 Web サイト中の製品名は、各社の商標です。</center>
		</div>
	</body>
</html>
