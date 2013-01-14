<?php

include_once 'check_picture_folder.php';

function getParentFolder($dir_path) {
  $dirs = split("/", $dir_path);
  $prev_dir_path = "";
  $prev_dir_count = count($dirs);
  foreach ($dirs as $key => $value) {
    if ($key == $prev_dir_count - 1) {
      break;
    }
    if ($prev_dir_path == "") {
      $prev_dir_path = $value;
    } else {
      $prev_dir_path = $prev_dir_path."/".$value;
    }
  }
  if (!checkPictureFolder($prev_dir_path, $PICTURE_FOLDER_TOP)) {
    $prev_dir_path = "";
  }

  return $prev_dir_path;
}
