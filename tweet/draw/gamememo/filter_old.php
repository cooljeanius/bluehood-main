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

        $gamememo = imagecreatefromjpeg($_FILES['gamememo']['tmp_name']);
        $width = imagesx($gamememo);
        $height = imagesy($gamememo);

        $canvas = imagecreatetruecolor($width, $height);
        $color_id = [imagecolorallocate($canvas, 0, 0, 0), imagecolorallocate($canvas, 255, 255, 255)];
        $i = 0;
        for($y = 0; $y < $height; $y++) for($x = 0; $x < $width; $x++){
		$rgb = imagecolorat($gamememo, $x, $y);
		$hsv = rgb2hsv(['red' => ($rgb >> 16) & 0xFF, 'green' => ($rgb >> 8) & 0xFF, 'blue' => $rgb & 0xFF]);

		if ($hsv['s'] < 0.625){
			if ($hsv['v'] < 0.50){	/*黒 (black)*/
				$color = 0;
			}else{	/*白 (white)*/
				$color = 1;
			}
		}else{
			if (($hsv['h'] >= 180.0)&&($hsv['h'] < 315.0)){	/*青 (blue)*/
				$color = ($x)%3 != 0;
			}else{	/*赤 (red)*/
				$color = ($x+$y)%4 != 0;
			}
		}
		
                imagesetpixel($canvas, $x, $y, $color_id[$color]);
        }

	ob_start();
	imagepng($canvas);
	imagedestroy($canvas);
	$canvas_url = 'data:image/png;base64,'.base64_encode(ob_get_contents());
	ob_end_clean();
?>
<img src="<?php echo $canvas_url; ?>">
