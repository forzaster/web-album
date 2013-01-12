<?php
$jpeg_file = $_GET['id'];
$jpeg_file = stripcslashes($jpeg_file);
header("Content-Type: image/jpeg");

$PREFIX = "../Pictures";
if (strncmp($jpeg_file, $PREFIX, strlen($PREFIX)) != 0) {
  return;
}

echo exif_thumbnail($jpeg_file);
?>