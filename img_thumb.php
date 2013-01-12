<?php
include 'config.php';

$jpeg_file = $_GET['id'];
$jpeg_file = stripcslashes($jpeg_file);
header("Content-Type: image/jpeg");

if (strncmp($jpeg_file, $PICTURE_FOLDER_TOP, strlen($PICTURE_FOLDER_TOP)) != 0) {
  return;
}

echo exif_thumbnail($jpeg_file);
?>