<?php
include_once 'config.php';
function msg($log) {
  echo $log;
}

$prevStartDate = "";
$prevEndDate = "";

$dir = $_GET['dir'];

session_start();
if (isset($_SESSION["PREV_START_DATE"])) {
    $prevStartDate = $_SESSION["PREV_START_DATE"];
}
if (isset($_SESSION["PREV_END_DATE"])) {
    $prevEndDate = $_SESSION["PREV_END_DATE"];
}

// connect sql
$link = mysql_connect('localhost', 'admin', 'password');
if (!$link) {
  msg("DB connect failed");
  return;
}

$db_selected = mysql_select_db('pictures', $link);
if (!$db_selected){
  msg("DB connect fail");
  return;
}

mysql_set_charset('utf8');

$where = "";

if ($prevStartDate != "") {
  $where = "WHERE DATE <='$prevStartDate'";
}

if ($dir != null) {
switch ($dir) {
  case "next":
    if ($prevEndDate != "") {
      $where = "WHERE DATE <'$prevEndDate'";
    }
    break;
  case "prev":
    if ($prevStartDate != "") {
      $where = "WHERE DATE >'$prevStartDate'";
    }
    break;
  case "init":
    $where = "";
    break;
  default:
    break;
}
}

$queryCondition = "SELECT PATH,DATE FROM pictures $where ORDER BY DATE DESC";
$result = mysql_query($queryCondition);
if (!$result) {
  msg("SELECT query failed.");
}

if ($dir == "prev") {
  seekToPrev($result);
}
showNextPage($result, $PHOTO_COLUMNS);

// close DB
$close_flag = mysql_close($link);
if (!$close_flag){
  msg("DB disconnect failed.");
}

function seekToPrev($res) {
  $COUNT_IN_PAGE = 50;
  $count = mysql_num_rows($res);
  if ($count > $COUNT_IN_PAGE) {
    $idx = $count - $COUNT_IN_PAGE;
    $prevDateYm = '';
    while ($idx >= 0 && mysql_data_seek($res, $idx)) {
      $row = mysql_fetch_assoc($res);
      $date = $row['DATE'];
      $dateYm = date('Y/m', strtotime($date));
      if ($prevDateYm != $dateYm && $prevDateYm != '') {
        mysql_data_seek($res, $idx+1);
        break;
      }
      $prevDateYm = $dateYm;
      $idx -= 1;
    }
  }
}

function showNextPage($res, $photo_columns) {
  $COUNT_IN_PAGE = 50;
  $count = 0;
  $photoCount = 0;
  $prevDateYmd = "";
  $prevDateYm = "";
  $firstFlag = true;
  $finishFlag = false;
  while ($row = mysql_fetch_assoc($res)) {
    $path = $row['PATH'];
    $date = $row['DATE'];
    $dateYmd = date('Y/m/d', strtotime($date));
    $dateYm = date('Y/m', strtotime($date));
    if ($firstFlag == true) {
        $_SESSION["PREV_START_DATE"] = $date;
        $prevDateYm = $dateYm;
        $firstFlag = false;
    }
    if ($prevDateYm != $dateYm) {
        if ($finishFlag == true) {
            $_SESSION["PREV_END_DATE"] = $date;
            break;
        }
        $prevDateYm = $dateYm;
        msg("<hr>\n");
    }
    if ($prevDateYmd != $dateYmd) {
        $photoCount = 0;
        msg("<p>");
        msg($dateYmd);
        msg("<br>\n");
        $prevDateYmd = $dateYmd;
    }
    msg("<a href=\"./img.php?");
    msg("id=$path\" rel=\"lightbox1\">");
    msg("<img src=\"./img_thumb.php?id=");
    msg("$path\"></a>\n");
    $photoCount += 1;
    if ($photoCount == $photo_columns) {
        msg("<p>\n");
        $photoCount = 0;
    } else {
        msg("&nbsp;&nbsp;&nbsp;\n");
    }
    $count++;
    if ($count > $COUNT_IN_PAGE) {
        $finishFlag = true;
    }
  }
}

?>
