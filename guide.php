<?php
	include('/var/www/twiverse.php');
	$s = [
		'title' => ['ja' => 'BlueHood利用ガイド', 'en' => "BlueHood User's Guide"],
		'desc' => [
			'ja' => 'BlueHoodを安全に利用するために、このガイドを参考にしてください。<br>
			なお、このガイドはTwitter利用に関する制限を規定するものではありません。',
			'en' => "Please refer this guide to use BlueHood more safely. <br>
                	But, this doesn't regulate your Twitter rules. ",
		],
		'responsibility' => ['ja' => '免責事項', 'en' => 'Responsibility'],
		'res_desc' => [
			'ja' => 'BlueHoodは個人によって現在も開発中であり、不安定な要素を含みます。<br>
                        無保証で使用してくださるようお願いします。',
			'en' => 'BlueHood is now underdevelopng, so has no warranty. ',
		],
		'env' => ['ja' => '推奨環境', 'en' => 'Enviroment Requirements'],
		'env_desc' => [
			'ja' => 'BlueHoodは、Wii U・3DS・PS VITAからのアクセスを想定しています。<br>
                        PC・タブレット・スマホからは、Chromeでのアクセスを推奨します。<br>
                        パソコンではWii U版、スマホでは3DS版のページが表示されます。<br>
                        3DSからのお絵かき投稿はゲームメモを使用します。',
			'en' => "BlueHood is a game community for Wii U, 3DS, PS VITA. <br>
			Chrome browser is recommended to access from PC, tablet, and smartphone. <br>
			This site shows Wii U version pages for PC, and 3DS version pages for smartphone. <br>
			When posts a drawing from 3DS, you'll use gamememo application. ",
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
                        They mention also harassments and personal informations. <br>
                        For more information, please refer <a href="https://support.twitter.com/articles/253501" target="_top">Twitter rules</a>. '
		],
		'violation' => ['ja' => '違反投稿かな？と思ったら', 'en' => 'Report violations to Twitter'],
		'violation_desc' => [
			'ja' => 'BlueHoodは、ツイートに対する管理権および義務を有しません。<br>
			また、このガイドはTwitter利用に関する制限を規定するものではありません。<br>
			違反投稿と思われるツイートを見つけたら、<a href="https://support.twitter.com/articles/253501" target="_top">Twitterルール</a>と照らし合わせ、<a href="https://support.twitter.com/articles/486421">Twitterへ報告</a>してください。',
			'en' => 'BlueHood doesn\'t have rights and responsibilities of tweets. <br>
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
			'en' => 'BlueHood notification (<img src="'.ROOT_URL.'img/feed.png" alt="通知" style="width: 1em; "> icon) works through follow rules.
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
	<head>
		<style type="text/css">
			.card{
				text-align: center;
			}
			.card p{
				text-align: left;
			}
			h3{
				color: orange;
				border-bottom: 2px solid gold;
				margin-top: 0.5em;
				/*font-size: medium;*/
				text-align: left;
			}
		</style>
	</head>
	<?php head(); ?>
	<body>
		<h2 class="topbar"><?php l($s['title']); ?></h2>
		<div class="main paddingleft paddingright" style="padding-top: 1em; ">
			<span><?php l($s['desc']); ?></span>

			<span id="responsibility"></span>
			<h3><?php l($s['responsibility']); ?></h3>
			<p><?php l($s['res_desc']); ?></p>

			<h3><?php l($s['env']); ?></h3>
			<p><?php l($s['env_desc']); ?></p>

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
			<img src="<?php echo ROOT_URL; ?>img/twiverse/default.png" width="160px"><br>
			<br>
			<br>
			</div>

			<span id="violation"></span>
			<div class="card card-article">
			<h3><?php l($s['violation']); ?></h3>
			<p><?php l($s['violation_desc']); ?></p>
			<br>
			<br>
			<img src="<?php echo ROOT_URL; ?>img/ban.png" width="160px"><br>
			<br>
			<br>
			</div>

			<!-- <h3 id="tweet">投稿について</h3>
			<p>BlueHoodからの投稿には、ハッシュタグが最大2つ自動的に追加されます。<br>
			さらにハッシュタグを追加する場合は、付け過ぎに注意してください。<br>
			ハッシュタグは、2つまでの使用が推奨されています(<a href="https://support.twitter.com/articles/243963">ハッシュタグ（「#」記号）とは？</a>)。</p> -->

			<h3 id="notification"><?php l($s['note']); ?></h3>
			<p><?php l($s['note_desc']); ?></p>
			<br>
			<center>本 Web サイト中の製品名は、各社の商標です。</center>
		</div>
	</body>
</html>
