<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="stylesheet" type="text/css" href="resource/lightbox.css" media="screen,tv" />
<script type="text/javascript" charset="UTF-8" src="resource/lightbox_plus_min.js"></script>
<title>Home Picture</title>
</head>
<body>

<?php

// directory check
$PREFIX = "../../Pictures";
$dir_path = $_GET['dir'];
$dir_path = stripcslashes($dir_path);
if (strncmp($dir_path, $PREFIX, strlen($PREFIX)) != 0) {
  echo "<h1>Cannot access!!</h1>";
  return;
}

// generate parent directory
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

if (strncmp($prev_dir_path, $PREFIX, strlen($PREFIX)) != 0) {
  $prev_dir_path = "";
}

// title(header of contents)
$title = str_replace("../", "", $dir_path);
echo "<h2>$title</h2>\n";
echo "<hr>\n";

// crawl and list pictures
if ($dir = opendir($dir_path)) {
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
	    echo "<a href=\"./img.php?";
	    echo "id=$file_path\" rel=\"lightbox1\">";
	    echo "<img src=\"./img_thumb.php?id=";
	    echo "$file_path\"></a><br>\n";
	    echo "$w x $h : ";
	    /*
	    echo "<a href=\"./picture.php?";
	    echo "dir=$dir_path&";
	    echo "id=$file_path&";
	    echo "name=$file\">";
	    echo "<img src=\"./img_thumb.php?id=";
	    echo "$file_path\"></a><br>\n";
	    */
	  } else {
	    echo "No thumbnail<br>\n";
	  }
	  echo "$file<p>\n";
	}
      }
    }
  } 
  closedir($dir);
}

// footer
echo "<hr>";
if ($prev_dir_path != "") {
  echo "<a href=\"./album_list.php?dir=$prev_dir_path\">上へ</a>\n";
} else {
  echo "<button>test</button>\n";
}
?>

</body>
</html>
