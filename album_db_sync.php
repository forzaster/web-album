<?php

// funtions
function msg($log) {
  echo "document.write(\"$log<br>\");";
  //echo "postMessage(\"$log<br>\");";
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

$PREFIX = "../Pictures";
$dir_path = $_GET['dir'];
$dir_path = stripcslashes($dir_path);
$progress_value = $_GET['progress'];
msg("sync $dir_path");
if (strncmp($dir_path, $PREFIX, strlen($PREFIX)) != 0) {
  msg("Cannot access!!");
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

if (strncmp($prev_dir_path, $PREFIX, strlen($PREFIX)) != 0) {
  $prev_dir_path = "";
}

// title(header of contents)
$title = str_replace("../", "", $dir_path);

// generate parent dir path
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


echo "var progress = document.getElementsByTagName(\"progress\")[0];";
echo "progress.value = $progress_value;";

/*
// dump DB contents
$result = mysql_query('SELECT ID,NAME,PATH,DATE FROM pictures');
if (!$result) {
  msg("SELECT query failed.");
}

echo "DB contents are <br>\n";
while ($row = mysql_fetch_assoc($result)) {
  print('ID='.$row['ID']);
  print(',NAME='.$row['NAME']);
  print(',PATH='.$row['PATH']);
  print(',DATE='.$row['DATE']);
  print('<br>');
}
*/

// close DB
$close_flag = mysql_close($link);
if (!$close_flag){
  msg("DB disconnect failed.");
}

msg("DB disconnect success");

?>
