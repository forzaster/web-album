<?php
include_once 'config.php';

$jpeg_file = $_GET['id'];
$jpeg_file = stripcslashes($jpeg_file);
header("Content-Type: image/jpeg");

include_once 'check_picture_folder.php';
if (!checkPictureFolder($jpeg_file, $PICTURE_FOLDER_TOP)) {
  return;
}

echo exif_thumbnail($jpeg_file);
?>