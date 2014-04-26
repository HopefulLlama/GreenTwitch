<?php
session_start();
# Read background image
$image = ImageCreateFromPng ("captcha100x40.png");

# Randomise the text colour
$red = rand(50,100);
$green = rand(50,100);
$blue = rand(50,100);
$textColour = ImageColorAllocate($image, $red, $green, $blue);

# Randomly select a character string
$charArray = array('a','b','c','d','e','f','g','h','j','k','m','n','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','J','K','L','M','N','P','Q','R','T','U','V','W','X','Y','Z','2','3','4','6','7','8','9');
$captchaString = '';
shuffle($charArray);
for ($i=0; $i<8; $i++) $captchaString .= $charArray[$i];
$_SESSION['captcha']=$captchaString;

# Edit the image
ImageString($image, 5, 15, 10, $captchaString, $textColour);

# Enlarge the image
$bigImage = imagecreatetruecolor(200, 80);
imagecopyresized($bigImage, $image, 0, 0, 0, 0, 200, 80, 100, 40);

# Output the image as a low quality JPEG
header("Content-Type: image/jpeg");
Imagejpeg($bigImage, NULL, 8);

# clean up
ImageDestroy($image);
ImageDestroy($bigImage);


?>