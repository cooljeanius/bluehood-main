<?php
	include('/var/www/twiverse.php');
	$s = [
		'title' => [
			'ja' => "コミュニティ設立・変更依頼フォーム",
			'en' => "Community creation/change request form",
		],
		'l1' => [
			'ja' => "※Wii U・3DS以外の端末からも受け付けています。",
			'en' => "※The form accepts submissions from browsers other than Wii U or 3DS.",
		],
		'l2' => [
			'ja' => "すべての依頼にお応えできるわけではありません。",
			'en' => "Not all requests can be answered.",
		],
		'l3' => [
			'ja' => "3DS・Wii Uのコミュニティ設立については、まずは投稿画面よりスクリーンショットを添付してみてください。",
			'en' => "To create a 3DS or Wii U community, please attach a screenshot from the posting screen first.",
		],
		'l4' => [
			'ja' => "コミュニティを設立・変更するソフトのスクリーンショットを添付してください。",
			'en' => "Attach a screenshot from the software that will establish or modify the community.",
		],
		'l5' => [
			'ja' => "スクリーンショットは「識別情報」と「ファイル名」を抽出し、「ご利用のブラウザ情報」とともに@Twiverse_adminにメール送信されます。",
			'en' => "The form extracts identification information and the file name from the screenshot, and e-mails it to @Twiverse_admin along with your browser information.",
		],
		'l6' => [
			'ja' => "画像データ本体は破棄されます。",
			'en' => "The image data itself is discarded.",
		],
		'l7' => [
			'ja' => "「送信」を押すと、送信情報の確認画面が表示されます。",
			'en' => "When you press \"Send\", the confirmation screen for the transmission of information is displayed.",
		],
		'name' => ['ja' => "ソフト名", 'en' => "Software name", ],
		'contact' => ['ja' => "連絡先(省略可)", 'en' => "Contact info (optional)", ],
		'handle' => ['ja' => "@Twitterアカウント名", 'en' => "@Twitter account name", ],
		'sending' => ['ja' => "処理中…", 'en' => "processing…", ],
		//'' => ['ja' => "", 'en' => "", ],
	];
?>

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
