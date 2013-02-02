<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<?php
include_once 'lightbox_setting.php';
?>
<title>Home Picture All</title>
</head>
<body>

<?php
include_once 'config.php';
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

showNextPage($result, $PHOTO_COLUMNS);

// close DB
$close_flag = mysql_close($link);
if (!$close_flag){
  msg("DB disconnect failed.");
}

echo "<br><hr>";

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
        $prevDateYm = $dateYm;
        $firstFlag = false;
    }
    if ($prevDateYm != $dateYm) {
        if ($finishFlag == true) {
            break;
        }
        $prevDateYm = $dateYm;
        echo "<hr>\n";
    }
    if ($prevDateYmd != $dateYmd) {
        $photoCount = 0;
        echo "<p>";
        echo $dateYmd;
        echo "<br>\n";
        $prevDateYmd = $dateYmd;
    }
    echo "<a href=\"./img.php?";
    echo "id=$path\" rel=\"lightbox1\">";
    echo "<img src=\"./img_thumb.php?id=";
    echo "$path\"></a>\n";
    $photoCount += 1;
    if ($photoCount == $photo_columns) {
        echo "<p>\n";
        $photoCount = 0;
    } else {
        echo "&nbsp;&nbsp;&nbsp;\n";
    }
    $count++;
    if ($count > $COUNT_IN_PAGE) {
        $finishFlag = true;
    }
  }
}

?>

</body>
</html>
