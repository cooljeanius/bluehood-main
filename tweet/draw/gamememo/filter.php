<?php
	include('/var/www/twiverse.php');
	$s = [
		//'' => ['ja' => "", 'en' => "", ],
	];

function rgb2hsv($rgb){
  $r = $rgb['red'] / 255;
  $g = $rgb['green'] / 255;
  $b = $rgb['blue'] / 255;
   
  $max = max($r, $g, $b);
  $min = min($r, $g, $b);
  $v = $max;
   
  if($max === $min){
    $h = 0;
  } else if($r === $max){
    $h = 60 * ( ($g - $b) / ($max - $min) ) + 0;
  } else if($g === $max){
    $h = 60 * ( ($b - $r) / ($max - $min) ) + 120;
  } else {
    $h = 60 * ( ($r - $g) / ($max - $min) ) + 240;
  }
  if($h < 0) $h = $h + 360;
 
  $s = ($v != 0) ? ($max - $min) / $max : 0;
   
  $hsv = array("h" => $h, "s" => $s, "v" => $v);
  return $hsv;
}

	function tone($x, $y, $tone){
		switch($tone){
			case 0:
			return ($x)%2 != 0;;
			case 1:
			return ($x)%3 != 0;
			case 2:
			return ($x)%4 != 0;;

			case 3:
			return ($y)%2 != 0;;
			case 4:
			return ($y)%3 != 0;;
			case 5:
			return ($y)%4 != 0;;

			case 6:
			return ($x+$y)%3 != 0;
			case 7:
			return ($x+$y)%4 != 0;
			case 8:
			return ($x+$y)%5 != 0;

			case 9:
			return ($x-$y)%3 != 0;
			case 10:
			return ($x-$y)%4 != 0;
			case 11:
			return ($x-$y)%5 != 0;
		}
	}

	if (empty($_POST['enable'])){
		$canvas_base64 = base64_encode($_SESSION['gamememo']);
	}else{
	$gamememo_path = tempnam('/tmp', 'php').'.png';
        file_put_contents($gamememo_path, $_SESSION['gamememo']);
        exec('sync');
        $gamememo = imagecreatefromjpeg($gamememo_path);
	unlink($gamememo_path);
        $width = imagesx($gamememo);
        $height = imagesy($gamememo);

        $canvas = imagecreatetruecolor($width, $height);
        $color_id = [imagecolorallocate($canvas, 0, 0, 0), imagecolorallocate($canvas, 255, 255, 255)];
        $i = 0;
        for($y = 0; $y < $height; $y++) for($x = 0; $x < $width; $x++){
		$rgb = imagecolorat($gamememo, $x, $y);
		$hsv = rgb2hsv(['red' => ($rgb >> 16) & 0xFF, 'green' => ($rgb >> 8) & 0xFF, 'blue' => $rgb & 0xFF]);

		if ($hsv['s'] < 0.625){
			if ($hsv['v'] < 0.50){	/*黒 (black) */
				$color = 0;
			}else{	/*白 (white) */
				$color = 1;
			}
		}else{
			if (($hsv['h'] >= 180.0)&&($hsv['h'] < 315.0)){	/*青 (blue) */
				$color = tone($x, $y, (int)$_POST['blue']);
			}else{	/*赤 (red)*/
				$color = tone($x, $y, (int)$_POST['red']);
			}
		}
		
                imagesetpixel($canvas, $x, $y, $color_id[$color]);
        }

	ob_start();
	imagepng($canvas);
	imagedestroy($canvas);
	$canvas_base64 = base64_encode(ob_get_contents());
	ob_end_clean();
	}
	$_SESSION['draw'] = $canvas_base64;
?>
<div id="draw"><?php echo $canvas_base64; ?></div>
