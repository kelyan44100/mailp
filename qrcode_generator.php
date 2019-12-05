<?php
require_once("phpqrcode/qrlib.php");

// Path where the images will be saved
$filepath = 'img/myimage.png';
// Image (logo) to be drawn
$logopath = 'img/html_logo.png';
// qr code content
$codeContents = 'http://ourcodeworld.com';
// Create the file in the providen path
// Customize how you want
QRcode::png($codeContents,$filepath , QR_ECLEVEL_H, 20);

// Start DRAWING LOGO IN QRCODE

$QR = imagecreatefrompng($filepath);

// START TO DRAW THE IMAGE ON THE QR CODE
$logo = imagecreatefromstring(file_get_contents($logopath));
$QR_width = imagesx($QR);
$QR_height = imagesy($QR);

$logo_width = imagesx($logo);
$logo_height = imagesy($logo);

// Scale logo to fit in the QR Code
$logo_qr_width = $QR_width/3;
$scale = $logo_width/$logo_qr_width;
$logo_qr_height = $logo_height/$scale;

imagecopyresampled($QR, $logo, $QR_width/3, $QR_height/3, 0, 0, $logo_qr_width, $logo_qr_height, $logo_width, $logo_height);

// Save QR code again, but with logo on it
imagepng($QR,$filepath);

// End DRAWING LOGO IN QR CODE

// Ouput image in the browser
echo '<img src="'.$filepath.'" />';
?>