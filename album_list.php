<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">-->
<meta name="viewport" content="width=550, maximum-scale=1">     

<?php

include_once 'config.php';
if (strlen($COLORBOX) > 0) {
  include_once 'colorbox_setting.php';
} else if (strlen($LIGHTBOX) > 0) {
  include_once 'lightbox_setting.php';
}
?>

<title>Home Picture</title>
</head>
<body>

<?php

// directory check
$dir_path = $_GET['dir'];
$dir_path = stripcslashes($dir_path);

include_once 'check_picture_folder.php';
if (!checkPictureFolder($dir_path, $PICTURE_FOLDER_TOP)) {
  echo "<h1>Cannot access!!</h1>";
  return;
}

// parent dir
include_once 'get_parent_folder.php';
$prev_dir_path = getParentFolder($dir_path);

// title(header of contents)
$title = str_replace("../", "", $dir_path);
echo "<h2>$title</h2>\n";
echo "<hr>\n";

// crawl and list pictures
if ($dir = opendir($dir_path)) {
  $photoCount = 0;
  while (($file = readdir($dir)) !== false) {
    if ($file != "." && $file != "..") {
      $file_path = "$dir_path"."/"."$file";
      if (is_dir($file_path)) {
	echo "<a href=\"";
	echo "album_list.php?dir=$file_path\">";
	echo "$file";
	echo "</a>";
	echo "<br>\n";
      } else {
	if (preg_match("/^\w.*\.jpg/i", $file) == 1) {
	  if (exif_thumbnail($file_path, $width, $height) != FALSE) {
	    list($w,$h) = getimagesize($file_path);

	    if (file_exists($LIGHTBOX) || file_exists($COLORBOX)) {
	      echo "<a class=\"group1\" href=\"./img.php?";
	      echo "id=$file_path\" rel=\"lightbox1\">";
	      echo "<img src=\"./img_thumb.php?id=";
	      echo "$file_path\"></a>";
	    } else {
	      echo "<a href=\"./picture.php?";
	      echo "dir=$dir_path&";
	      echo "id=$file_path&";
	      echo "name=$file\">";
	      echo "<img src=\"./img_thumb.php?id=";
	      echo "$file_path\"></a>";
	    }
	    //echo "$w x $h : ";
	  } else {
	    echo "No thumbnail<br>\n";
	  }
	  //echo "$file<p>\n";
          $photoCount += 1;
          if ($photoCount == 3) {
              echo "<p>";
              $photoCount = 0;
          } else {
              echo "&nbsp;&nbsp;&nbsp;";
          }
	}
      }
    }
  } 
  closedir($dir);
}

// footer
include_once 'make_footer.php';
makeFooter($prev_dir_path);

?>

</body>
</html>
