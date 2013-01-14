<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title>Picture DB</title>
</head>
<body>

<?php

include 'config.php';

// funtions
function insert($name, $path, $date) {
  $sql = "SELECT ID FROM pictures WHERE PATH = '$path'";
  $result = mysql_query($sql);
  if (!$result) {
    echo "<p>SELECT query failed</p>\n";
    return;
  }
  $row = mysql_fetch_assoc($result);
  if ($row == FALSE) {
    $sql = "INSERT INTO pictures (ID, NAME, PATH, DATE) VALUES (0, '$name', '$path', '$date')";
    $result_flag = mysql_query($sql);
    if (!$result_flag) {
      echo "<p>INSERT query failed : $file_path</p>\n";
    }
  } else {
    echo "$path has already added<br>\n";
  }
}

function crawlDir($path) {
  if ($dir = opendir($path)) {
    while (($file = readdir($dir)) !== false) {
      if ($file != "." && $file != "..") {
	$file_path = "$path"."/"."$file";
	if (is_dir($file_path)) {
	  // recursive call
	  echo "Dir $file_path<br>\n";
	  crawlDir($file_path);
	} else {
	  if (preg_match("/^\w.*\.jpg/i", $file) == 1) {
	    $exif = exif_read_data($file_path);
	    if ($exif != FALSE) {
	      $time = $exif['DateTime'];
	    }
	    echo "insert $file_path<br>\n";
	    insert($file, $file_path, $time);
	  } else {
	    echo "Not jpg $file_path<br>\n";
	  }
	}
      }
    } 
    closedir($dir);
  }
}


// main
$dir_path = $_GET['dir'];
include 'check_picture_folder.php';
if (!checkPictureFolder($dir_path, $PICTURE_FOLDER_TOP)) {
  echo "<h1>Cannot access!!</h1>\n";
  return;
}

// generate parent directory path
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
  return;
}

// title(header of contents)
$title = str_replace("../", "", $dir_path);
echo "<h2>$title</h2>\n";
echo "<hr>\n";

// connect sql
$link = mysql_connect('localhost', 'admin', 'password');
if (!$link) {
  echo "<p>DB connect failed</p>\n";
  return;
}

echo "DB connect success<br>\n";

$db_selected = mysql_select_db('pictures', $link);
if (!$db_selected){
  echo "<p>DB connect fail</p>\n";
  return;
}

echo 'Select "pictures" table.<br>';

mysql_set_charset('utf8');

// crawl and insert picture to DB
crawlDir($dir_path);

// dump DB contents
$result = mysql_query('SELECT ID,NAME,PATH,DATE FROM pictures');
if (!$result) {
  echo '<p>SELECT query failed.</p>';
}

echo "DB contents are <br>\n";
while ($row = mysql_fetch_assoc($result)) {
  print('ID='.$row['ID']);
  print(',NAME='.$row['NAME']);
  print(',PATH='.$row['PATH']);
  print(',DATE='.$row['DATE']);
  print('<br>');
}

// close DB
$close_flag = mysql_close($link);
if (!$close_flag){
  echo '<p>DB disconnect failed.</p>';
}

echo 'DB disconnect success.<br>';

// footer
include 'make_footer.php';
makeFooter($prev_dir_path);

?>

</body>
</html>
