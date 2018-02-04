<?php
	include('/var/www/twiverse.php');

	function dropTweet($status, $twitter, $hide, $comm_id){
		//$emb_def = $twitter->get('statuses/oembed', ['id' => $status->id_str, 'maxwidth' => '240'])->html;
		//$emb_3ds = emb_3ds($status);
		$hide = $hide?'true':'false';

		mysql_start();
		//mysql_query("insert into tweet (id, emb_def, emb_3ds, screen_name, hide, time, comm_id, object) values (".$status->id.", '".$emb_def."', '".$emb_3ds."', '".$status->user->screen_name."', ".$hide.", now(), '".$comm_id."', '".json_encode($status)."')");
		mysql_query("insert into tweet (id, screen_name, hide, time, comm_id) values (".$status->id.", '".$status->user->screen_name."', ".$hide.", now(), '".$comm_id."')");
		mysql_query("update comm set post_n=post_n+1 where id='".$comm_id."'");
		$res = mysql_fetch_assoc(mysql_query("select collection_id from comm where id = '".$comm_id."'"));
		$collection_id = $res['collection_id'];
		mysql_close();

		$twitter_admin = twitter_admin();
		$twitter_admin->post('collections/entries/add', ['id' => 'custom-'.$collection_id, 'tweet_id' => $status->id_str]);
		// All Posts
		$twitter_admin->post('collections/entries/add', ['id' => 'custom-932243624233979905', 'tweet_id' => $status->id_str]);
		// マイページ
		if (isset($_SESSION['twitter']['account']['collection_id'])) $twitter->post('collections/entries/add', ['id' => $_SESSION['twitter']['account']['collection_id'], 'tweet_id' => $status->id_str]);
	}

	function complete_page($comm_id){

		if (!isset($comm_id)) header('Location: '.DOMAIN.ROOT_URL.'view/');

		mysql_start();
		$res = mysql_fetch_assoc(mysql_query("select name, list_id from comm where id = '".$comm_id."'"));
		$comm_name = $res['name'];
		$list_id = $res['list_id'];
		mysql_close();

		$twitter = twitter_start();
		$registered = isset($twitter->get('lists/members/show', ['list_id' => $list_id, 'screen_name' => $_SESSION['twitter']['account']['user']->screen_name, 'include_entities' => 'false', 'skip_status' => 'true'])->id);

		if ($registered) header('Location: '.DOMAIN.ROOT_URL.'view/?comm_id='.$comm_id);

		$s = [
			'title' => ['ja' => '送信しました！', 'en' => 'Sent! '],
			'invite' => ['ja' => "
				あなたのアカウントをTwitter ".$comm_name." リストに登録しませんか？<br>
                                登録すると、以降はスクリーンショットを添付せずに<br>
                                ".$comm_name." コミュニティに投稿できるようになります！<br>
                                <br>
                                リストは公開設定です。
			", 'en' => "
				Would you like to reqister your account to Twitter ".$comm_name." list? <br>
                                If you registered, you can post to ".$comm_name." community without screenshots! <br>
                                <br>
                                The list is set to public. 
			"],
			'register' => ['ja' => "登録する", 'en' => "Register"],
			'registered' => ['ja' => "登録しました！", 'en' => "Registered! "],
		];
			?>
<!DOCTYPE html>
<html>
	<?php head(); ?>
	<body>
		<h2 class="topbar">送信しました！</h2>
		<div class="main paddingleft paddingright">
			<br>
			<div class="whitebox">
				<p><?php l($s['invite']); ?><!--<br>
				※登録者数が5000人を超えるコミュニティの場合、<br>
				登録が外れてしまう場合があります。--></p>
				<a id="register" class="linkbutton"><?php l($s['register']); ?></a><br>
				<a class="linkbutton" href="<?php echo DOMAIN.ROOT_URL.'view/?comm_id='.$comm_id; ?>">コミュニティに移動</a>

				<script type="text/javascript">
					$('#register').click(function(){
						$.post(tweet_url + 'reglist.php', {id: '<?php echo $list_id; ?>', screen_name: '<?php echo $_SESSION['twitter']['account']['user']->screen_name; ?>'});
						$(this).css('background-color', 'lightgray');
						$(this).css('color', 'inherit');
						$(this).html('<?php l($s['registered']); ?>');
						$(this).prop("disabled", true);
					});
				</script>
			</div>
		</div>
	</body>
</html>
		<?php
	}
?>
