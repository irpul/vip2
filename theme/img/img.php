<?php
session_start();

//$font = realpath(__DIR__ . '/../') . '/font/BYekan.ttf';

class CaptchaSecurityImages {
	//global $font;

	//var $font = '../font/BYekan.ttf';
	//var $font = 'b.ttf';
	//public $font = realpath(__DIR__ . '/../') . '/font/BYekan.ttf';
	//public $name;
	//$font = realpath(__DIR__ . '/../') . '/font/BYekan.ttf';

	function generateCode($characters) {
		/* list all possible characters, similar looking characters and vowels have been removed */
		$possible = '123456789123456789';
		$code = '';
		$i = 0;
		while ($i < $characters) { 
			$code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
			$i++;
		}
		return $code;
	}

	function CaptchaSecurityImages($width='120',$height='40',$characters='6') {



		$code = $this->generateCode($characters);
		/* font size will be 75% of the image height */
		$font_size = $height * 0.75;
		$image = @imagecreate($width, $height) or die('Cannot initialize new GD image stream');
		/* set the colours */
		$background_color = imagecolorallocate($image, 20, 200, 255);
		$text_color = imagecolorallocate($image, 251, 59, 78);
		$noise_color = imagecolorallocate($image, 255, 255, 255);
		/* generate random dots in background */
		for( $i=0; $i<($width*$height)/3; $i++ ) {
			imagefilledellipse($image, mt_rand(0,$width), mt_rand(0,$height), 1, 1, $noise_color);
		}
		/* generate random lines in background */
		for( $i=0; $i<($width*$height)/150; $i++ ) {
			imageline($image, mt_rand(0,$width), mt_rand(0,$height), mt_rand(0,$width), mt_rand(0,$height), $noise_color);
		}
		/* create textbox and add text */
		$font = realpath(__DIR__ . '/../') . '/font/BYekan.ttf';
		//echo $this->font;
		//$textbox = imagettfbbox($font_size, 0, $this->font, $code) or die('Error in imagettfbbox function');
		$textbox = imagettfbbox($font_size, 0, $font, $code) or die('Error in imagettfbbox function');
		$x = ($width - $textbox[4])/2;
		$y = ($height - $textbox[5])/2;
		//imagettftext($image, $font_size, 0, $x, $y, $text_color, $this->font , $code) or die('Error in imagettftext function');
		imagettftext($image, $font_size, 0, $x, $y, $text_color, $font , $code) or die('Error in imagettftext function');
		/* output captcha image to browser */
		header('Content-Type: image/jpeg');
		imagejpeg($image);
		imagedestroy($image);
		//echo $code;
		$_SESSION['img'] = strtolower($code);
	}

}

$width = isset($_GET['width']) ? $_GET['width'] : '140';
$height = isset($_GET['height']) ? $_GET['height'] : '35';
$characters = isset($_GET['characters']) && $_GET['characters'] > 1 ? $_GET['characters'] : '5';
if(isset($_GET['whoisthisg']))
	die('Ym9sbHl3b29kaWhhLmly');
$captcha = new CaptchaSecurityImages($width,$height,$characters);


?>