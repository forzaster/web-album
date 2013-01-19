<?php

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

deleteAll();

// close DB
$close_flag = mysql_close($link);
if (!$close_flag){
  msg("DB disconnect failed.");
}

msg("DB disconnect success");
?>
