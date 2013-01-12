<?php

function rotate($file, $degree) {
  $img = ImageCreateFromJPEG($file);
  $newimg = ImageRotate($img, $degree, 0);
  ImageJPEG($newimg);
}

$jpeg_file = $_GET['id'];
$jpeg_file = stripcslashes($jpeg_file);

header("Content-Type: image/jpeg");

$PREFIX = "../../Pictures";
if (strncmp($jpeg_file, $PREFIX, strlen($PREFIX)) != 0) {
  return;
}

$exif = exif_read_data($jpeg_file);
if ($exif == FALSE) {
  readfile($jpeg_file);
} else {
  $ort = $exif['Orientation'];
  switch($ort) {
  case 1: // nothing
    readfile($jpeg_file);
    break;

  case 2: // horizontal flip
    //$image->flipImage($public,1);
    readfile($jpeg_file);
    break;
                                
  case 3: // 180 rotate left
    rotate($jpeg_file, 180);
    break;
                    
  case 4: // vertical flip
    //$image->flipImage($public,2);
    rotate($jpeg_file, 180);
    break;
                
  case 5: // vertical flip + 90 rotate right
    //$image->flipImage($public, 2);
    rotate($jpeg_file, -90);
    break;
                
  case 6: // 90 rotate right
    rotate($jpeg_file, -90);
    break;
                
  case 7: // horizontal flip + 90 rotate right
    //$image->flipImage($public,1);    
    rotate($jpeg_file, 90);
    break;
                
  case 8:    // 90 rotate left
    rotate($jpeg_file, 90);
    break;
  default:
    readfile($jpeg_file);
    break;
  }
}

?>