<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<?php
include 'lightbox_setting.php';
?>
<title>Home Picture All</title>
</head>
<body>

<?php

function msg($log) {
  echo $log;
}

// title(header of contents)
echo "<h2>All</h2>\n";
echo "<hr>\n";

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

$result = mysql_query('SELECT PATH,DATE FROM pictures ORDER BY DATE DESC');
if (!$result) {
  msg("SELECT query failed.");
}

showNextPage($result);

// close DB
$close_flag = mysql_close($link);
if (!$close_flag){
  msg("DB disconnect failed.");
}

echo "<br><hr>";

function showNextPage($res) {
  $COUNT_IN_PAGE = 50;
  $count = 0;
  while ($row = mysql_fetch_assoc($res)) {
    if ($COUNT_IN_PAGE > $count) {
      $path = $row['PATH'];
      $date = $row['DATE'];
      echo "<p>$date<br><a href=\"./img.php?";
      echo "id=$path\" rel=\"lightbox1\">";
      echo "<img src=\"./img_thumb.php?id=";
      echo "$path\"></a></p>\n";
    } else {
      break;
    }
    $count++;
  }
}

?>

</body>
</html>
