<?php

include_once 'config.php';

// funtions
function msg($log) {
  //echo "document.write(\"$log<br>\");";
  //echo "postMessage(\"$log<br>\");";
  echo "$log<br>";
}

function deleteAll() {
  $sql = "TRUNCATE TABLE pictures";
  $result = mysql_query($sql);
  if (!$result) {
    msg("Delete all failed.");
    return;
  }
  msg("Delete all successed.");
}

function insert($name, $path, $datetime) {
  $sql = "SELECT ID FROM pictures WHERE PATH = '$path'";
  $result = mysql_query($sql);
  if (!$result) {
    msg("SELECT query failed(1) : $file_path");
    return;
  }
  $row = mysql_fetch_assoc($result);
  if ($row == FALSE) {
    $sql = "INSERT INTO pictures (ID, NAME, PATH, DATE) VALUES (0, '$name', '$path', cast('$datetime' as datetime))";
    $result_flag = mysql_query($sql);
    if (!$result_flag) {
      msg("INSERT query failed(2) : $file_path");
    }
  } else {
    //msg("$path has already added.");
  }
}

function crawlDir($path) {
  if ($dir = opendir($path)) {
    $c = count($dir);
    while (($file = readdir($dir)) !== false) {
      if ($file != "." && $file != "..") {
	$file_path = "$path"."/"."$file";
	if (is_dir($file_path)) {
	  // recursive call
	  //msg("Dir $file_path");
	  crawlDir($file_path);
	} else {
	  if (preg_match("/^\w.*\.jpg/i", $file) == 1) {
	    //$exif = exif_read_data($file_path);
	    $time = filemtime($file_path);
	    $datetime = date("Y-m-d H:i:s", $time);
	    //msg("insert $file_path $time $datetime");
	    insert($file, mysql_real_escape_string($file_path), $datetime);
	  } else {
	    //msg("Not jpg $file_path");
	  }
	}
      }
    } 
    msg("Dir $path done.");
    closedir($dir);
  }
}



// main
header("Content-type: application/x-javascript");
msg("<br>");

$dir_path = $_GET['dir'];
if ($dir_path == null) {
  $dir_path = $PICTURE_FOLDER_TOP;
}
$dir_path = stripcslashes($dir_path);
$progress_value = $_GET['progress'];
msg("sync $dir_path");

include_once 'check_picture_folder.php';
if (!checkPictureFolder($dir_path, $PICTURE_FOLDER_TOP)) {
  msg("Cannot access!!");
  return;
}

// title(header of contents)
$title = str_replace("../", "", $dir_path);

// parent dir
include_once 'get_parent_folder.php';
$prev_dir_path = getParentFolder($dir_path);

// connect sql
$link = mysql_connect('localhost', 'admin', 'password');
if (!$link) {
  msg("DB connect failed");
  return;
}

msg("DB connect success");

$db_selected = mysql_select_db('pictures', $link);
if (!$db_selected){
  msg("DB connect fail");
  return;
}

msg("Select pictures table");

mysql_set_charset('utf8');

// delete db
//deleteAll();

// crawl and insert picture to DB
crawlDir($dir_path);

//echo "var progress = document.getElementsByTagName(\"progress\")[0];";
//echo "progress.value = $progress_value;";

// close DB
$close_flag = mysql_close($link);
if (!$close_flag){
  msg("DB disconnect failed.");
}

msg("DB disconnect success");

?>
