<?php
	include('/var/www/twiverse.php');
	$s = [
		'safe' => ['ja' => "安全です", 'en' => "It's safe", ],
		'safeish' => ['ja' => "おそらく安全です", 'en' => "Probably safe", ],
		'fityfifty' => ['ja' => "可能性があります", 'en' => "May be dangerous", ],
		'unsafeish' => ['ja' => "おそらく危険です", 'en' => "Probably dangerous", ],
		'unsafe' => ['ja' => "危険です", 'en' => "It's dangerous", ],
		'title' => [
			'ja' => "Google Vision API による危険画像の検出プログラム",
			'en' => "Dangerous image detection program using Google Vision API",
		],
		'author' => ['ja' => " by グレたノコノコ", 'en' => " by Stray Koopa Troopa", ],
		'desc' => [
			'ja' => "Google Vision API を使って、ネット上の悪意のあるユーザが投稿する危険な画像を自動で検出します。",
			'en' => "Use Google Vision API to automatically detect dangerous images posted by malicious users on the net.",
		],
		'l2' => [
			'ja' => "なお、このプログラムは Vision API の機能を体験していただくために公開していますので、突然ページを削除することがあります。"
			'en' => "Please be aware that this program has been released for the purpose of testing the functions of the Vision API, so the page may suddenly be deleted.",
		],
		'img' => ['ja' => "検査画像", 'en' => "Test image", ],
		'submit' => ['ja' => "画像検査実行", 'en' => "Run image test", ],
		'output' => ['ja' => "Vision API の解析結果 (生データ)", 'en' => "Analysis result of Vision API (raw data)", ],
		'output2' => ['ja' => "ざっくり要約すると", 'en' => "Roughly summarized", ],
		'adult' => ['ja' => "性的な画像: ", 'en' => "Sexual image:", ],
		'medical' => ['ja' => "グロッス画像: ", 'en' => "Gross image:", ],
		'violence' => ['ja' => "暴力的な画像: ", 'en' => "Violent image:", ],
		'spoof' => ['ja' => "コラ画像: ", 'en' => "Shopped image:", ],
		'prefix' => ['ja' => "ちなみに、これは", 'en' => "By the way, this is a ", ],
		'verb' => ['ja' => "の画像です。", 'en' => " image.", ],
		'source' => ['ja' => "PHP ソースコード", 'en' => "PHP source code", ],
		//'' => ['ja' => "", 'en' => "", ],
	];
	twitter_admin(); // アクセス帯域制限 (Access band limit)

	function reshtml($res){
		$html = "";
		switch($res){
			case "VERY_UNLIKELY":
			$html = '<font color="green">安全です</font>';
			break;
			case "UNLIKELY":
			$html = '<font color="green">おそらく安全です</font>';
			break;
			case "POSSIBLE":
			$html = '<font color="yellow">可能性があります</font>';
			break;
			case "LIKELY":
			$html = '<font color="yellowgreen">おそらく危険です</font>';
			break;
			case "VERY_LIKELY":
			$html = '<font color="yellowgreen">危険です</font>';
			break;
			default:
			$html = htmlspecialchars($res);
			break;
		}
		return $html;
	}
?>

<!DOCTYPE html>
<html>
	<head>
	</head>
	<body>
		<h3>Google Vision API による危険画像の検出プログラム</h3> by グレたノコノコ
		<p><a href="https://cloud.google.com/vision/?hl=ja">Google Vision API</a> を使って、
		ネット上の悪意のあるユーザが投稿する危険な画像を自動で検出します。<br>
		なお、このプログラムは Vision API の機能を体験していただくために公開していますので、
		突然ページを削除することがあります。</p>
		<form method="post" enctype="multipart/form-data">
			検査画像<br>
			<input name="image" type="file" accept="image/*"><br>
			<br>
			<input type="submit" value="画像検査実行">
		</form>
		<?php
if (isset($_FILES["image"]["tmp_name"])){
	$image = base64_encode(file_get_contents($_FILES["image"]["tmp_name"]));
	?>
		<img src="data:image;base64,<?php echo $image; ?>">
	<?php
	// リクエスト用のJSONを作成 (Create JSON for request)
	$json = json_encode( array(
		"requests" => array(
			array(
				"image" => array(
					"content" => $image,
				) ,
				"features" => array(
					array(
						"type" => "WEB_DETECTION" ,
						"maxResults" => 1,
					) ,
					array(
						"type" => "SAFE_SEARCH_DETECTION",
					) ,
				) ,
			) ,
		) ,
	) ) ;
	// リクエストを実行 (Execute request)
	$curl = curl_init() ;
	curl_setopt( $curl, CURLOPT_URL, "https://vision.googleapis.com/v1/images:annotate?key=".GOOGLE_CLOUD_PLATFORM_KEY) ;
	curl_setopt( $curl, CURLOPT_HEADER, true ) ; 
	curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, "POST" ) ;
	curl_setopt( $curl, CURLOPT_HTTPHEADER, array( "Content-Type: application/json" ) ) ;
	curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false ) ;
	curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true ) ;
	if( isset($referer) && !empty($referer) ) curl_setopt( $curl, CURLOPT_REFERER, $referer ) ;
	curl_setopt( $curl, CURLOPT_TIMEOUT, 15 ) ;
	curl_setopt( $curl, CURLOPT_POSTFIELDS, $json ) ;
	$res1 = curl_exec( $curl ) ;
	$res2 = curl_getinfo( $curl ) ;
	curl_close( $curl ) ;
	// 取得したデータ (The acquired data)
	$json = substr( $res1, $res2["header_size"] ) ;	// 取得したJSON (The obtained JSON)
	$header = substr( $res1, 0, $res2["header_size"] ) ; // レスポンスヘッダー (Response header)
	$vision_res = json_decode($json);

		?>
		<h4>Vision API の解析結果 (生データ)</h4>
		<textarea cols="80" rows="8"><?php echo htmlspecialchars(print_r($vision_res, true)); ?></textarea>
		<h4>ざっくり要約すると</h4>
		<ul>
			<?php $safe = $vision_res->responses[0]->safeSearchAnnotation; ?>
			<li>性的な画像: <?php echo reshtml($safe->adult); ?></li>
			<li>グロッス画像: <?php echo reshtml($safe->medical); ?></li>
			<li>暴力的な画像: <?php echo reshtml($safe->violence); ?></li>
			<li>コラ画像: <?php echo reshtml($safe->spoof); ?></li>
		</ul>
		ちなみに、これは<b>「<?php echo htmlspecialchars($vision_res->responses[0]->webDetection->webEntities[0]->description); ?>」</b>の画像です。
		<?php
}
		?>
		<h4>PHP ソースコード</h4>
		<textarea cols="160" rows="16"><?php echo htmlspecialchars(file_get_contents("index.php")); ?></textarea>
	</body>
</html>

