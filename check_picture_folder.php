<?php

function checkPictureFolder($dir_path, $root) {
  if (strncmp($dir_path, $root, strlen($root)) != 0) {
    return false;
  }
  return true;
}

?>
