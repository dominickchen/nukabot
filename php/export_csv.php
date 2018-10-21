<?php
	
// $export_array = $_POST['export_array'];
$export_array = array(array("date","value"));
// $export_array = array_merge($export_array, unserialize($_POST['export_array']));
$posted_array = unserialize($_POST['export_array']);

$value_type = $_POST['value_type'];
$time_mode = $_POST['time_mode'];

foreach ($posted_array as $key => $value)
{
	$export_array []= [$key, $value];
}
/*
echo "<pre>";
echo var_export($export_array,true);
echo "</pre>";
*/

/*
foreach ($export_array as $array) {
		echo "{$array[0]}, {$array[1]}\n";	
}
*/



$FileName = "nukabot-" . $value_type . "-".$time_mode."-data-at-".date("Y-m-d H:i:s",time()).".csv";
header('Content-Type: application/csv'); 
header('Content-Disposition: attachment; filename="' . $FileName . '"'); 

foreach ($export_array as $array) {
		echo "{$array[0]}, {$array[1]}\n";	
}
exit();


?>

