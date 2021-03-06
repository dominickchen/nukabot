<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
	<title>NUKA</title>
    <script src="utils.js"></script>

    <style>
    canvas{
        -moz-user-select: none;
        -webkit-user-select: none;
        -ms-user-select: none;
    }
    </style>
</head>
	
	<body>
		
		<h1 style="text-align: center;">NukaDoko(v0.1) Stats</h1>
		
		<!-- PH -->
		<canvas id="canvas1" width="75%"></canvas>
	
			<div style="clear:both;"></div>

		<!-- Cond -->
		<canvas id="canvasCond" width="75%"></canvas>
	
			<div style="clear:both;"></div>

		<!-- TEMP SOIL -->
		<canvas id="canvas2" width="75%"></canvas>

			<div style="clear:both;"></div>

		<!-- TEMP EXT -->
		<canvas id="canvas3" width="75%"></canvas>

			<div style="clear:both;"></div>

		<!-- MOISTURE SOIL -->
		<canvas id="canvas4" width="75%"></canvas>

			<div style="clear:both;"></div>

		<!-- HUMIDITY EXT -->
		<canvas id="canvas5" width="75%"></canvas>

			<div style="clear:both;"></div>

		<!-- GAS1 -->
		<canvas id="canvas6" width="75%"></canvas>

			<div style="clear:both;"></div>

		<!-- GAS2 -->
		<canvas id="canvas7" width="75%"></canvas>

			<div style="clear:both;"></div>

		<!-- GAS3 -->
		<canvas id="canvas8" width="75%"></canvas>

			<div style="clear:both;"></div>

		<!-- GAS4 -->
		<canvas id="canvas9" width="75%"></canvas>

			<div style="clear:both;"></div>

		<!-- GAS5 -->
		<canvas id="canvas10" width="75%"></canvas>

			<div style="clear:both;"></div>

		<!-- GAS6 -->
		<canvas id="canvas11" width="75%"></canvas>

			<div style="clear:both;"></div>

		<!-- GAS7 -->
		<canvas id="canvas12" width="75%"></canvas>

			<div style="clear:both;"></div>

		<!-- GAS8 -->
		<canvas id="canvas13" width="75%"></canvas>

			<div style="clear:both;"></div>

		<!-- GAS9 -->
		<canvas id="canvas14" width="75%"></canvas>

			<div style="clear:both;"></div>

<?php

	$timeMode = "secondly";
	
	if (isset($_GET['timeMode']) && $_GET['timeMode'] != "")
	{
		switch ($_GET['timeMode'])
		{
			case "hourly":
				$timeMode = "hourly";
				break;
			case "daily":
				$timeMode = "dayly";
				break;
			case "weekly":
				$timeMode = "weekly";
				break;
			default:
				$timeMode = "secondly";
		}
	}

	$criteria_date = date("Y-m-d H:i:s", time() - (60*60*24));
	// データベース接続情報
	$url = "ttjs.ctimigi6vjbg.ap-northeast-1.rds.amazonaws.com";
	$user = "root";
	$password = "medialive2008";
	$database = "nuka";
	
	// データベース接続処理
	$connect = mysql_connect($url,$user,$password) or die("can't connect");
	$db = mysql_select_db($database,$connect) or die("can't Select database");

	switch ($timeMode)
	{
		case "secondly":
			$sql = "SELECT * FROM set_01 WHERE created_at > \"" . $criteria_date . "\" ORDER BY id ASC";
			break;
		case "hourly":
			$sql = "SELECT * FROM `set_01` WHERE created_at > (DATE_SUB(CURDATE(), INTERVAL 1 HOUR))";
			break;
		case "daily":
			$sql = "SELECT * FROM `set_01` WHERE created_at > (DATE_SUB(CURDATE(), INTERVAL 1 DAY))";
			break;
		case "weekly":
			$sql = "SELECT * FROM `set_01` WHERE created_at > (DATE_SUB(CURDATE(), INTERVAL 1 WEEK))";
			break;
	}

	$result = mysql_query($sql, $connect) or die("can't submit SQL");

	$phArray = $conductivityArray = $tempSoilArray = $tempExtArray = $humidityExtArray = $moistureIntArray = 
	$gasHCHOArray = $gasNH3Array = $gasCH4Array = $gasC4H10Array = $gasNO2Array = $gasC2H5OHArray = 
	$gasCOArray = $gasC3H8Array = $gasH2Array = array();
	
    while ($row = mysql_fetch_assoc($result)) {
		if ($row["ph"] != "")
		{
			$datetime = $row["created_at"];
			$phArray[$datetime] = $row["ph"];
		} elseif ($row["conductivity"] != "")
		{
			$datetime = $row["created_at"];
			$conductivityArray[$datetime] = $row["conductivity"];			
		} elseif ($row["temperature_soil"] != "")
		{
			$datetime = $row["created_at"];
			$tempSoilArray[$datetime] = $row["temperature_soil"];			
		} elseif ($row["temperature_external"] != "")
		{
			$datetime = $row["created_at"];
			$tempExtArray[$datetime] = $row["temperature_external"];			
		} elseif ($row["temperature_soil"] != "")
		{
			$datetime = $row["created_at"];
			$tempSoilArray[$datetime] = $row["temperature_soil"];			
		} elseif ($row["humidity"] != "")
		{
			$datetime = $row["created_at"];
			$humidityExtArray[$datetime] = $row["humidity"];			
		} elseif ($row["moisture"] != "")
		{
			$datetime = $row["created_at"];
			$moistureIntArray[$datetime] = $row["moisture"];			
		} elseif ($row["gas_HCHO"] != "")
		{
			$datetime = $row["created_at"];
			$gasHCHOArray[$datetime] = $row["gas_HCHO"];			
		} elseif ($row["gas_NH3"] != "")
		{
			$datetime = $row["created_at"];
			$gasNH3Array[$datetime] = $row["gas_NH3"];			
		} elseif ($row["gas_CH4"] != "")
		{
			$datetime = $row["created_at"];
			$gasCH4Array[$datetime] = $row["gas_CH4"];			
		} elseif ($row["gas_C4H10"] != "")
		{
			$datetime = $row["created_at"];
			$gasC4H10Array[$datetime] = $row["gas_C4H10"];			
		} elseif ($row["gas_NO2"] != "")
		{
			$datetime = $row["created_at"];
			$gasNO2Array[$datetime] = $row["gas_NO2"];			
		} elseif ($row["gas_C2H5OH"] != "")
		{
			$datetime = $row["created_at"];
			$gasC2H5OHArray[$datetime] = $row["gas_C2H5OH"];			
		} elseif ($row["gas_CO"] != "")
		{
			$datetime = $row["created_at"];
			$gasCOArray[$datetime] = $row["gas_CO"];			
		} elseif ($row["gas_C3H8"] != "")
		{
			$datetime = $row["created_at"];
			$gasC3H8Array[$datetime] = $row["gas_C3H8"];			
		} elseif ($row["gas_H2"] != "")
		{
			$datetime = $row["created_at"];
			$gasH2Array[$datetime] = $row["gas_H2"];			
		}
    }
    mysql_free_result($result);
    mysql_close($connect) or die("can't closed");


?>

<code>
<?php 
	$time_string_ph = "\"";
	$data_string_ph = "";
	foreach ($phArray as $key => $value)
	{
		$time_string_ph .= $key . "\", \"";
		$data_string_ph .= $value . ",";
	}
	$time_string_ph .= "\"";		
?>
</code>

    <script>
        var config1= {
            type: 'line',
            data: {
                labels: [<?=$time_string_ph?>],
                datasets: [{
                    label: "ぬか床・土中pH",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [<?=$data_string_ph?>],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'ぬか床・土中pH'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };

    </script>
    

<code>
<?php 
	$time_string_cond = "\"";
	$data_string_cond = "";
	foreach ($conductivityArray as $key => $value)
	{
		$time_string_cond .= $key . "\", \"";
		$data_string_cond .= $value . ",";
	}
	$time_string_cond .= "\"";		
?>
</code>

    <script>
        var configCond = {
            type: 'line',
            data: {
                labels: [<?=$time_string_cond?>],
                datasets: [{
                    label: "ぬか床・電解（塩分）",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [<?=$data_string_cond?>],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'ぬか床・電解（塩分）'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };

    </script>    		

    

<code>
<?php 
	$time_string_temp_soil = $time_string_temp_ext = "\"";
	$data_string_temp_soil = $data_string_temp_ext = "";
	foreach ($tempSoilArray as $key => $value)
	{
		$time_string_temp_soil .= $key . "\", \"";
		$data_string_temp_soil .= $value . ",";
	}
	$time_string_temp_soil .= "\"";	
	foreach ($tempExtArray as $key => $value)
	{
		$time_string_temp_ext .= $key . "\", \"";
		$data_string_temp_ext .= $value . ",";
	}
	$time_string_temp_ext .= "\"";	
		
?>
</code>

    <script>
        var config2 = {
            type: 'line',
            data: {
                labels: [<?=$time_string_temp_soil?>],
                datasets: [{
                    label: "ぬか床・土中温度",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [<?=$data_string_temp_soil?>],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'ぬか床・土中温度'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };

    </script>   

    <script>
        var config3 = {
            type: 'line',
            data: {
                labels: [<?=$time_string_temp_ext?>],
                datasets: [{
                    label: "ぬか床・環境温度",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [<?=$data_string_temp_ext?>],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'ぬか床・環境温度'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };

    </script>   

<code>
<?php 
	$time_string_moisture = $time_string_humidity = "\"";
	$data_string_moisture = $data_string_humidity = "";
	foreach ($humidityExtArray as $key => $value)
	{
		$time_string_humidity .= $key . "\", \"";
		$data_string_humidity .= $value . ",";
	}
	$time_string_humidity .= "\"";	
	foreach ($moistureIntArray as $key => $value)
	{
		$time_string_moisture .= $key . "\", \"";
		$data_string_moisture .= $value . ",";
	}
	$time_string_moisture .= "\"";
		
?>
</code>

    <script>
        var config4 = {
            type: 'line',
            data: {
                labels: [<?=$time_string_moisture?>],
                datasets: [{
                    label: "ぬか床・土中水分",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [<?=$data_string_moisture?>],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'ぬか床・土中水分'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };
    </script>   
    <script>
        var config5 = {
            type: 'line',
            data: {
                labels: [<?=$time_string_humidity?>],
                datasets: [{
                    label: "ぬか床・環境湿度",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [<?=$data_string_humidity?>],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'ぬか床・環境湿度'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };
    </script>   


<code>
<?php 
	$time_string_gas1 = $time_string_gas2 = $time_string_gas3 = $time_string_gas4 = $time_string_gas5 = $time_string_gas6 = $time_string_gas7 = $time_string_gas8 = $time_string_gas9 = "\"";
	$data_string_gas1 = $data_string_gas2 = $data_string_gas3 = $data_string_gas4 = $data_string_gas5 = $data_string_gas6 = $data_string_gas7 = $data_string_gas8 = $data_string_gas9 = "";
	foreach ($gasHCHOArray as $key => $value)
	{
		$time_string_gas1 .= $key . "\", \"";
		$data_string_gas1 .= $value . ",";
	}
	$time_string_gas1 .= "\"";	
	foreach ($gasNH3Array as $key => $value)
	{
		$time_string_gas2 .= $key . "\", \"";
		$data_string_gas2 .= $value . ",";
	}
	$time_string_gas2 .= "\"";
	foreach ($gasCH4Array as $key => $value)
	{
		$time_string_gas3 .= $key . "\", \"";
		$data_string_gas3 .= $value . ",";
	}
	$time_string_gas3 .= "\"";
	foreach ($gasC4H10Array as $key => $value)
	{
		$time_string_gas4 .= $key . "\", \"";
		$data_string_gas4 .= $value . ",";
	}
	$time_string_gas4 .= "\"";
	foreach ($gasNO2Array as $key => $value)
	{
		$time_string_gas5 .= $key . "\", \"";
		$data_string_gas5 .= $value . ",";
	}
	$time_string_gas5 .= "\"";
	foreach ($gasC2H5OHArray as $key => $value)
	{
		$time_string_gas6 .= $key . "\", \"";
		$data_string_gas6 .= $value . ",";
	}
	$time_string_gas6 .= "\"";
	foreach ($gasCOArray as $key => $value)
	{
		$time_string_gas7 .= $key . "\", \"";
		$data_string_gas7 .= $value . ",";
	}
	$time_string_gas7 .= "\"";
	foreach ($gasC3H8Array as $key => $value)
	{
		$time_string_gas8 .= $key . "\", \"";
		$data_string_gas8 .= $value . ",";
	}
	$time_string_gas8 .= "\"";
	foreach ($gasH2Array as $key => $value)
	{
		$time_string_gas9 .= $key . "\", \"";
		$data_string_gas9 .= $value . ",";
	}
	$time_string_gas9 .= "\"";
		
?>
</code>

    <script>
        var config6 = {
            type: 'line',
            data: {
                labels: [<?=$time_string_gas1?>],
                datasets: [{
                    label: "ぬか床・HCHO",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [<?=$data_string_gas1?>],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'ぬか床・HCHO'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };
    </script>   
    <script>
        var config7 = {
            type: 'line',
            data: {
                labels: [<?=$time_string_gas2?>],
                datasets: [{
                    label: "ぬか床・NH3",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [<?=$data_string_gas2?>],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'ぬか床・NH3（アンモニア）'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };
    </script>   
    <script>
        var config8 = {
            type: 'line',
            data: {
                labels: [<?=$time_string_gas3?>],
                datasets: [{
                    label: "ぬか床・CH4",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [<?=$data_string_gas3?>],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'ぬか床・CH4（メタン）'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };
    </script>   
    <script>
        var config9 = {
            type: 'line',
            data: {
                labels: [<?=$time_string_gas4?>],
                datasets: [{
                    label: "ぬか床・C4H10",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [<?=$data_string_gas4?>],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'ぬか床・C4H10（ブタン）'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };
    </script>   
    <script>
        var config10 = {
            type: 'line',
            data: {
                labels: [<?=$time_string_gas5?>],
                datasets: [{
                    label: "ぬか床・NO2",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [<?=$data_string_gas5?>],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'ぬか床・NO2（二酸化窒素）'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };
    </script>   
    <script>
        var config11 = {
            type: 'line',
            data: {
                labels: [<?=$time_string_gas6?>],
                datasets: [{
                    label: "ぬか床・C2H5OH",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [<?=$data_string_gas6?>],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'ぬか床・C2H5OH（エタノール）'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };
    </script>   
    <script>
        var config12 = {
            type: 'line',
            data: {
                labels: [<?=$time_string_gas7?>],
                datasets: [{
                    label: "ぬか床・CO",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [<?=$data_string_gas7?>],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'ぬか床・CO（一酸化炭素）'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };
    </script>   
    <script>
        var config13 = {
            type: 'line',
            data: {
                labels: [<?=$time_string_gas8?>],
                datasets: [{
                    label: "ぬか床・C3H8",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [<?=$data_string_gas8?>],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'ぬか床・C3H8（プロパン）'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };
    </script>   
    <script>
        var config14 = {
            type: 'line',
            data: {
                labels: [<?=$time_string_gas9?>],
                datasets: [{
                    label: "ぬか床・H2",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [<?=$data_string_gas9?>],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'ぬか床・H2（水素）'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Time'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Value'
                        }
                    }]
                }
            }
        };
    </script>   


	<script>
        window.onload = function() {
            var ph = document.getElementById("canvas1").getContext("2d");
            window.myLine = new Chart(ph, config1);
            var cond = document.getElementById("canvasCond").getContext("2d");
            window.myLine = new Chart(cond, configCond);
            var tempSoil = document.getElementById("canvas2").getContext("2d");
            window.myPh = new Chart(tempSoil, config2);
            var tempExt = document.getElementById("canvas3").getContext("2d");
            window.myPh = new Chart(tempExt, config3);
            var moisture = document.getElementById("canvas4").getContext("2d");
            window.myPh = new Chart(moisture, config4);
            var humidity = document.getElementById("canvas5").getContext("2d");
            window.myPh = new Chart(humidity, config5);
//             var gas1 = document.getElementById("canvas6").getContext("2d");
//             window.myPh = new Chart(gas1, config6);
            var gas2 = document.getElementById("canvas7").getContext("2d");
            window.myPh = new Chart(gas2, config7);
            var gas3 = document.getElementById("canvas8").getContext("2d");
            window.myPh = new Chart(gas3, config8);
            var gas4 = document.getElementById("canvas9").getContext("2d");
            window.myPh = new Chart(gas4, config9);
            var gas5 = document.getElementById("canvas10").getContext("2d");
            window.myPh = new Chart(gas5, config10);
            var gas6 = document.getElementById("canvas11").getContext("2d");
            window.myPh = new Chart(gas6, config11);
            var gas7 = document.getElementById("canvas12").getContext("2d");
            window.myPh = new Chart(gas7, config12);
            var gas8 = document.getElementById("canvas13").getContext("2d");
            window.myPh = new Chart(gas8, config13);
            var gas9 = document.getElementById("canvas14").getContext("2d");
            window.myPh = new Chart(gas9, config14);
        };
        var colorNames = Object.keys(window.chartColors);		
	</script>


	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>


	</body>
	
</html>