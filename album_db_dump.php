<?php

// funtions
function msg($log) {
  echo "document.write(\"$log<br>\");";
  //echo "postMessage(\"$log<br>\");";
}

function dump() {
  $result = mysql_query('SELECT ID,NAME,PATH,DATE FROM pictures');
  if (!$result) {
    msg("SELECT query failed.");
  }

  msg("DB contents are");
  while ($row = mysql_fetch_assoc($result)) {
    msg('ID='.$row['ID'].',NAME='.$row['NAME'].',PATH='.$row['PATH'].',DATE='.$row['DATE']);
  }
}

// main
header("Content-type: application/x-javascript");
msg("<br>");

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

// dump DB contents
dump();

// close DB
$close_flag = mysql_close($link);
if (!$close_flag){
  msg("DB disconnect failed.");
}

msg("DB disconnect success");

echo "var progress = document.getElementsByTagName(\"progress\")[0];";
echo "progress.max = 100;";
echo "progress.value = 100;";

?>
