<?php include('/var/www/twiverse.php'); ?>

<!DOCTYPE html>
<html lang = "ja">
	<?php head(); ?>
	<body>
		<h2 class="topbar">コミュニティ設立・変更依頼フォーム</h2>
		<div class="main paddingleft paddingright">
			※Wii U・3DS以外の端末からも受け付けています。<br>
			すべての依頼にお応えできるわけではありません。<br>
			3DS・Wii Uのコミュニティ設立については、まずは投稿画面よりスクリーンショットを添付してみてください。<br>
			<p>コミュニティを設立・変更するソフトのスクリーンショットを添付してください。<br>
			スクリーンショットは「識別情報」と「ファイル名」を抽出し、「ご利用のブラウザ情報」とともに@Twiverse_adminにメール送信されます。<br>
			画像データ本体は破棄されます。<br>
			「送信」を押すと、送信情報の確認画面が表示されます。</p>
			<form action="shotrepo.php" method="post" enctype="multipart/form-data">
				<input name="selimg" type="file" accept="image/jpeg"><br>
				ソフト名<input name="name" type="text"><br>
				連絡先(省略可)<input name="contact" type="text" placeholder="@Twitterアカウント名"><br>
				<input type="submit" onclick="$(this).prop('disabled', true); $(this).attr('value', '処理中…'); submit(); ">
			</form>
		</div>
	</body>
</html>
