<?php

include 'jquery_setting.php';

echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"$COLORBOX/colorbox.css\" media=\"screen,tv\" />\n";
echo "<script type=\"text/javascript\" charset=\"UTF-8\" src=\"$COLORBOX/jquery.colorbox-min.js\"></script>\n";
echo "<script type=\"text/javascript\" charset=\"UTF-8\">\n";
echo "\$(document).ready(function() {\n";
echo "\$(\".group1\").colorbox({rel:'lightbox1'});\n});";
echo "</script>";

?>
