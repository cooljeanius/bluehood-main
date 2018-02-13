<?php
	function put_image($path){
		$basename = 'default.png';
		$month = (int)date('n');
		$day = (int)date('j');
		switch($month){
			case 1:
			if (($day == 1)||($day == 2)||($day == 3)) $basename = 'newyear.png';
			break;

			case 2:
			if (($day == 3)) $basename = 'setsubun.png';
			else if (($day == 14)) $basename = 'valen.png';
			break;

			case 12:
			if (($day == 24)||($day == 25)) $basename = 'christmas.png';
			else if ($day == 31) $basename = 'newyeareve.png';
			break;
		}
		//$basename = 'setsubun.png';

		echo file_get_contents($path.$basename);
	}
?>
