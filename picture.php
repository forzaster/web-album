<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Home Picture</title>
<?php
include 'config.php';

$dir_path = $_GET['dir'];
$dir_path = stripcslashes($dir_path);
$img_path = $_GET['id'];
$img_path = stripcslashes($img_path);
$img_name = $_GET['name'];
?>
<!--
<script type="text/javascript">
onload = function() {
  draw();
};
function draw() {
  var canvas = document.getElementById('canvas1');
  if ( ! canvas || ! canvas.getContext ) { return false; }
  var ctx = canvas.getContext('2d');
  /* Imageオブジェクトを生成 */
  var img = new Image();
  img.src = "<?php echo "./img.php?id=$img_path"; ?>";
  /* 画像が読み込まれるのを待ってから処理を続行 */
  img.onload = function() {
    ctx.drawImage(img, 0, 0, 200, 200);
  }
}
</script>
-->
</head>
<body>

<?php

include 'check_picture_folder.php';
if (!checkPictureFolder($dir_path, $PICTURE_FOLDER_TOP)) {
  echo "Cannot access!!";
  return;
}
if (!checkPictureFolder($img_path, $PICTURE_FOLDER_TOP)) {
  echo "Cannot access!!";
  return;
}

$prev_file = "";
$prev_file_path = "";
$found = FALSE;

if ($dir = opendir($dir_path)) {
  while (($file = readdir($dir)) !== false) {
    if ($file != "." && $file != "..") {
      $file_path = "$dir_path"."/"."$file";
      if ($file == $img_name) {
	if ($prev_file_path != "") {
	  echo "<a href=\"./picture.php?";
	  echo "dir=$dir_path&";
	  echo "id=$prev_file_path&";
	  echo "name=$prev_file\">";
	  echo "<<前へ</a> | ";
	}
	echo "$img_name";
	$found = TRUE;
      } else if ($found == TRUE) {
	if (preg_match("/^\w.*\.jpg/i", $file) == 1) {
	  echo " | ";
	  echo "<a href=\"./picture.php?";
	  echo "dir=$dir_path&";
	  echo "id=$file_path&";
	  echo "name=$file\">";
	  echo "次へ>></a>";
	}
	break;
      }
      if (preg_match("/^\w.*\.jpg/i", $file) == 1) {
	$prev_file = $file;
	$prev_file_path = $file_path;
      }
    }
  }
  if ($found == TRUE) {
    echo "<br><hr>";
  }
}

echo "<a href=\"./img.php?id=";
echo "$img_path\">";
echo "<img src=\"./img.php?id=";
echo "$img_path\" width=\"100%\"></a><br>";
echo "<font size=\"1\">写真をクリックするとオリジナルのサイズで表示します。</font>";
echo "<br><hr>";

$exif = exif_read_data($img_path);
if ($exif == FALSE) {
  echo "No image detail information.<br>";
} else {
  foreach ($exif as $key => $value) {
    if ($key == "FileName" ||
	$key == "FileSize" ||
	$key == "DateTime" ||
	$key == "Make" ||
	$key == "Model") {
      echo "$key : $value<br />\n";
    }
  }
}

include "make_footer.php";
makeFooter($dir_path);
?>

<!--
<br>
<canvas id="canvas1" width="1000" height="1000"></canvas>
-->
</body>
</html>
