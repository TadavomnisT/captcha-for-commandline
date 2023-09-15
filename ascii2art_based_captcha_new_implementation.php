<?php

function apply_wave_effect($image, $width, $height) {  
  $x_period = 20;
  $y_period = 20;
  $y_amplitude = 15;
  $x_amplitude = 15;
  $xp = $x_period * rand(1, 3);
  $k = rand(0, 100);
  for ($a = 0; $a < $width; $a++)
    imagecopy($image, $image, $a-1, sin($k + $a / $xp) * $x_amplitude, $a, 0, 1, $height);
  
  $yp = $y_period * rand(1, 2);
  $k = rand(0, 100);
  
  for ($a = 0; $a < $height; $a++)
    imagecopy($image, $image, sin($k + $a / $yp) * $y_amplitude, $a - 1, 0, $a, $width, 1);
  
  return $image;
}

$sgn = !rand(0, 1);
$a = $sgn ? rand(0, 500) : rand(500, 999);
$b = rand(0, 500);

$string = $a . ($sgn ? '+' : '-') . $b;
$answer = $sgn ? ($a + $b) : ($a - $b);

$width = 600;
$height = 300;
$img = imagecreatetruecolor($width, $height);

$background_color = imagecolorallocate($img, 255, 255, 255);
$text_color = imagecolorallocate($img, 0, 0, 0);

imagefilledrectangle($img, 0, 0, $width - 1, $height - 1, $background_color);

$font_files = ['zxx-noise.ttf', 'zxx-xed.ttf'];
$font_file = './' . $font_files[array_rand($font_files, 1)];
$font_size = 90;

$textBoundingBox = imagettfbbox($font_size, 0, $font_file, $string);
$text_width = $textBoundingBox[2] - $textBoundingBox[0];
$text_height = $textBoundingBox[7] - $textBoundingBox[1];
$text_x = ($width - $text_width) / 2;
$text_y = ($height - $text_height) / 2;

imagettftext($img, $font_size, 0, $text_x, $text_y, $text_color, $font_file, $string);
$img = apply_wave_effect($img, imagesx($img), imagesy($img));

ob_start();
imagepng($img);
imagedestroy($img);

file_put_contents($file_name = md5(microtime(true)), ob_get_clean());

$ascii_art = shell_exec("jp2a $file_name");
unlink($file_name);

echo "\n$ascii_art\n";

$input = readline('Say answer: ');

echo "\n " . ['Oops.. :(', 'Bravo.. :)']["$input" === "$answer"] . "\n\n";