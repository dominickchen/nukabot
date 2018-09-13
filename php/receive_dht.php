<?php

mysql_connect ('ttjs.ctimigi6vjbg.ap-northeast-1.rds.amazonaws.com', 'root', 'medialive2008') or die ('Could not connect: ' . mysql_error ());
mysql_select_db ('nuka') or die ('Could not select database on TTJS');
mysql_query ("SET NAMES utf8");

$h = $_GET['h'];
$t = $_GET['t'];
$m = $_GET['m'];

$insert_ttdata = <<<EOD
	INSERT IGNORE INTO humidityTemperature (humidity, temperature, moisture) VALUES ($h, $t, $m)
EOD;

mysql_query ($insert_ttdata) or die ('error at insert date record: ' . mysql_error ());
mysql_close();

echo "inserted data";

?>
