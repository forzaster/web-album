<?php

function makeFooter($prev_dir_path) {
  // footer
  echo "<hr>";
  if ($prev_dir_path != "") {
    echo "<a href=\"./album_list.php?dir=$prev_dir_path\">上へ</a>\n";
  }
}

?>
