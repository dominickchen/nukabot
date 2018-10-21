<?php

date_default_timezone_set('Asia/Tokyo');

mysql_connect ('ttjs.ctimigi6vjbg.ap-northeast-1.rds.amazonaws.com', 'root', 'medialive2008') or die ('Could not connect: ' . mysql_error ());
mysql_select_db ('nuka') or die ('Could not select database on TTJS');
mysql_query ("SET NAMES utf8");

$cond = $_GET['cond'];

$insert_ttdata = <<<EOD
	INSERT IGNORE INTO set_01 (conductivity) VALUES ($cond)
EOD;

mysql_query ($insert_ttdata) or die ('error at insert date record: ' . mysql_error ());
mysql_close();

echo "inserted data";

?>
