<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<?php
include_once 'lightbox_setting.php';
?>

<script type="text/javascript" charset="UTF-8" src="javascript/myLib.js"></script>

<script language="javascript" type="text/javascript">
function loadPhotos(args) {
  var container = document.getElementById("contents");
  container.innerHTML = "loading...";

  var req = new createXMLHttpRequest();
  req.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var container = document.getElementById("contents");
      container.innerHTML = req.responseText;
    }
  }
  var adr = "./album_all_page.php";
  if (args != null) {
      adr += "?" + args;
  }
  req.open("GET", adr);
  req.send(null);

}

</script>

<title>Home Picture All</title>
</head>
<body onload="loadPhotos(null);">

<h2>Picture All</h2>
<hr>

<div id="contents"></div>

<hr>
<input type=button value='prev' onclick='loadPhotos("dir=prev");'/>
<input type=button value='next' onclick='loadPhotos("dir=next");'/>
</body>
</html>
