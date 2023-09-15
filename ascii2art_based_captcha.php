<?php

if (rand(0,100) > 50)
{
    $sgn = true;
    $a = rand (0,500);
    $b = rand (0,500);
}
else
{
    $sgn = false;
    $a = rand (500,1000);
    $b = rand (0,500);
}

$answer = ($sgn) ? ($a+$b) : ($a-$b) ;

$string = $a . (($sgn) ? "+" : "-") . $b;

$width = 600;
$height = 300;
$im = imagecreatetruecolor($width, $height);

$background_color = imagecolorallocate($im, 255, 255, 255);
$text_color = imagecolorallocate($im, 0, 0, 0);

imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $background_color);

$font_file = (rand(0,100) > 50) ? "./zxx-noise.ttf" : "./zxx-xed.ttf" ;
$font_size = 100;
$y = 180;

imagettftext($im, $font_size, 0, 10, $y, $text_color, $font_file, $string);

$im = applyWave($im, imagesx($im), imagesy($im));

ob_start();
header('Content-type: image/png');
imagepng($im);
imagedestroy($im);
file_put_contents( $fname = md5( microtime(true) ) , ob_get_clean() );

echo shell_exec( "jp2a " . $fname );
unlink( $fname );

function applyWave($image, $width, $height)
{    
    $x_period = 20;
    $y_period = 20;
    $y_amplitude = 15;
    $x_amplitude = 15;
    $xp = $x_period*rand(1,3);
    $k = rand(0,100);
    for ($a = 0; $a<$width; $a++)
        imagecopy($image, $image, $a-1, sin($k+$a/$xp)*$x_amplitude, $a, 0, 1, $height);
    $yp = $y_period*rand(1,2);
    $k = rand(0,100);
    for ($a = 0; $a<$height; $a++)
        imagecopy($image, $image, sin($k+$a/$yp)*$y_amplitude, $a-1, 0, $a, $width, 1);
    return $image;
}

?>