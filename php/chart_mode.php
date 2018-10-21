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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

</head>
	
	<body>
		
		<h1 style="text-align: center;">NukaDoko(v0.1) Stats</h1>
		<p>
			<ol type="1">
			<li><a href="?timeMode=secondly">過去24時間の全てのデータ</a></li>
			<li><a href="?timeMode=hourly">過去の全てのデータを1時間毎にサンプリング</a></li>
			<li><a href="?timeMode=trihourly">過去の全てのデータを3時間毎にサンプリング</a></li>
			<li><a href="?timeMode=daily">過去の全てのデータを1日毎にサンプリング</a></li>
			<li><a href="?timeMode=weekly">過去の全てのデータを1周間毎にサンプリング</a></li>
			</ol>
		</p>		
		
<?php

	$timeMode = "secondly";
	
	if (isset($_GET['timeMode']) && $_GET['timeMode'] != "")
	{
		switch ($_GET['timeMode'])
		{
			case "hourly":
				$timeMode = "hourly";
				break;
			case "trihourly":
				$timeMode = "trihourly";
				break;
			case "daily":
				$timeMode = "daily";
				break;
			case "weekly":
				$timeMode = "weekly";
				break;
			default:
				$timeMode = "secondly";
		}
	}

	$phArray = $conductivityArray = $temperature_soilArray = $temperature_externalArray = $humidityArray = $moistureArray = 
	$gas_NH3Array = $gas_CH4Array = $gas_C4H10Array = $gas_NO2Array = $gas_C2H5OHArray = 
	$gas_COArray = $gas_C3H8Array = $gas_H2Array = array();

	$columnArray = ["ph","conductivity","temperature_soil","temperature_external","humidity","moisture","gas_NH3","gas_CH4","gas_C4H10","gas_NO2","gas_C2H5OH","gas_CO","gas_C3H8","gas_H2"];

	$criteria_date = date("Y-m-d H:i:s", time() - (60*60*24));
	// データベース接続情報
	$url = "ttjs.ctimigi6vjbg.ap-northeast-1.rds.amazonaws.com";
	$user = "root";
	$password = "medialive2008";
	$database = "nuka";
		
	foreach ($columnArray as $column)
	{
		// データベース接続処理
		$connect = mysql_connect($url,$user,$password) or die("can't connect");
		$db = mysql_select_db($database,$connect) or die("can't Select database");
	
		switch ($timeMode)
		{
			case "secondly":
				$sql = "SELECT * FROM set_01 WHERE {$column} IS NOT NULL AND created_at > \"" . $criteria_date . "\" ORDER BY id ASC";
				break;

			case "hourly":
				$sql = "SELECT {$column}, created_at, FLOOR(UNIX_TIMESTAMP(created_at)/(60 * 60)) AS timekey FROM set_01 WHERE {$column} IS NOT NULL GROUP BY timekey";
				break;

			case "trihourly":
				$sql = "SELECT {$column}, created_at, FLOOR(UNIX_TIMESTAMP(created_at)/(60 * 60 * 3)) AS timekey FROM set_01 WHERE {$column} IS NOT NULL GROUP BY timekey";
				break;

			case "daily":
				$sql = "SELECT {$column}, created_at, FLOOR(UNIX_TIMESTAMP(created_at)/(60 * 60 * 24)) AS timekey FROM set_01 WHERE {$column} IS NOT NULL GROUP BY timekey";
				break;

			case "weekly":
				$sql = "SELECT {$column}, created_at, FLOOR(UNIX_TIMESTAMP(created_at)/(60 * 60 * 24 * 7)) AS timekey FROM set_01 WHERE {$column} IS NOT NULL GROUP BY timekey";
				break;
				
			default:
				$sql = "SELECT * FROM set_01 WHERE {$column} IS NOT NULL AND created_at > \"" . $criteria_date . "\" ORDER BY id ASC";				
		}
				
		$result = mysql_query($sql, $connect) or die("can't submit SQL");

		while ($row = mysql_fetch_assoc($result)) {
			$datetime = $row["created_at"];
			${$column . 'Array'}[$datetime] = $row[$column];
		}
	    mysql_free_result($result);
	    mysql_close($connect) or die("can't closed");
	}

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
        var config_ph= {
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
        var config_cond = {
            type: 'line',
            data: {
                labels: [<?=$time_string_cond?>],
                datasets: [{
                    label: "ぬか床・電解（塩分）",
                    backgroundColor: window.chartColors.gray,
                    borderColor: window.chartColors.gray,
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
	foreach ($temperature_soilArray as $key => $value)
	{
		$time_string_temp_soil .= $key . "\", \"";
		$data_string_temp_soil .= $value . ",";
	}
	$time_string_temp_soil .= "\"";	
	foreach ($temperature_externalArray as $key => $value)
	{
		$time_string_temp_ext .= $key . "\", \"";
		$data_string_temp_ext .= $value . ",";
	}
	$time_string_temp_ext .= "\"";	
		
?>
</code>

    <script>
        var config_temp_soil = {
            type: 'line',
            data: {
                labels: [<?=$time_string_temp_soil?>],
                datasets: [{
                    label: "ぬか床・土中温度",
                    backgroundColor: "#A0522D",
                    borderColor: "#A0522D",
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
        var config_temp_ext = {
            type: 'line',
            data: {
                labels: [<?=$time_string_temp_ext?>],
                datasets: [{
                    label: "ぬか床・環境温度",
                    backgroundColor: window.chartColors.blue,
                    borderColor: window.chartColors.blue,
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
	foreach ($humidityArray as $key => $value)
	{
		$time_string_humidity .= $key . "\", \"";
		$data_string_humidity .= $value . ",";
	}
	$time_string_humidity .= "\"";	
	foreach ($moistureArray as $key => $value)
	{
		$time_string_moisture .= $key . "\", \"";
		$data_string_moisture .= $value . ",";
	}
	$time_string_moisture .= "\"";
		
?>
</code>

    <script>
        var config_moisture_internal = {
            type: 'line',
            data: {
                labels: [<?=$time_string_moisture?>],
                datasets: [{
                    label: "ぬか床・土中水分",
                    backgroundColor: "#00BFFF",
                    borderColor: "#00BFFF",
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
        var config_humidity_external = {
            type: 'line',
            data: {
                labels: [<?=$time_string_humidity?>],
                datasets: [{
                    label: "ぬか床・環境湿度",
                    backgroundColor: window.chartColors.green,
                    borderColor: window.chartColors.green,
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
	$time_string_gas_NH3 = $time_string_gas_CH4 = $time_string_gas_C4H10 = $time_string_gas_NO2 = $time_string_gas_C2H5OH = $time_string_gas_CO = $time_string_gas_C3H8 = $time_string_gas_H2 = "\"";
	$data_string_gas_NH3 = $data_string_gas_CH4 = $data_string_gas_C4H10 = $data_string_gas_NO2 = $data_string_gas_C2H5OH = $data_string_gas_CO = $data_string_gas_C3H8 = $data_string_gas_H2 = "";

	foreach ($gas_NH3Array as $key => $value)
	{
		$time_string_gas_NH3 .= $key . "\", \"";
		$data_string_gas_NH3 .= $value . ",";
	}
	$time_string_gas_NH3 .= "\"";

	foreach ($gas_CH4Array as $key => $value)
	{
		$time_string_gas_CH4 .= $key . "\", \"";
		$data_string_gas_CH4 .= $value . ",";
	}
	$time_string_gas_CH4 .= "\"";

	foreach ($gas_C4H10Array as $key => $value)
	{
		$time_string_gas_C4H10 .= $key . "\", \"";
		$data_string_gas_C4H10 .= $value . ",";
	}
	$time_string_gas_C4H10 .= "\"";

	foreach ($gas_NO2Array as $key => $value)
	{
		$time_string_gas_NO2 .= $key . "\", \"";
		$data_string_gas_NO2 .= $value . ",";
	}
	$time_string_gas_NO2 .= "\"";

	foreach ($gas_C2H5OHArray as $key => $value)
	{
		$time_string_gas_C2H5OH .= $key . "\", \"";
		$data_string_gas_C2H5OH .= $value . ",";
	}
	$time_string_gas_C2H5OH .= "\"";

	foreach ($gas_COArray as $key => $value)
	{
		$time_string_gas_CO .= $key . "\", \"";
		$data_string_gas_CO .= $value . ",";
	}
	$time_string_gas_CO .= "\"";

	foreach ($gas_C3H8Array as $key => $value)
	{
		$time_string_gas_C3H8 .= $key . "\", \"";
		$data_string_gas_C3H8 .= $value . ",";
	}
	$time_string_gas_C3H8 .= "\"";

	foreach ($gas_H2Array as $key => $value)
	{
		$time_string_gas_H2 .= $key . "\", \"";
		$data_string_gas_H2 .= $value . ",";
	}
	$time_string_gas_H2 .= "\"";
		
?>
</code>

    <script>
        var config_gas_NH3 = {
            type: 'line',
            data: {
                labels: [<?=$time_string_gas_NH3?>],
                datasets: [{
                    label: "ぬか床・NH3",
                    backgroundColor: "#DDA0DD",
                    borderColor: "#DDA0DD",
                    data: [<?=$data_string_gas_NH3?>],
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
        var config_gas_CH4 = {
            type: 'line',
            data: {
                labels: [<?=$time_string_gas_CH4?>],
                datasets: [{
                    label: "ぬか床・CH4",
                    backgroundColor: "#DA70D6",
                    borderColor: "#DA70D6",
                    data: [<?=$data_string_gas_CH4?>],
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
        var config_C4H10 = {
            type: 'line',
            data: {
                labels: [<?=$time_string_gas_C4H10?>],
                datasets: [{
                    label: "ぬか床・C4H10",
                    backgroundColor: "#FF00FF",
                    borderColor: "#FF00FF",
                    data: [<?=$data_string_gas_C4H10?>],
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
        var config_gas_NO2 = {
            type: 'line',
            data: {
                labels: [<?=$time_string_gas_NO2?>],
                datasets: [{
                    label: "ぬか床・NO2",
                    backgroundColor: "#BA55D3",
                    borderColor: "#BA55D3",
                    data: [<?=$data_string_gas_NO2?>],
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
        var config_gas_C2H5OH = {
            type: 'line',
            data: {
                labels: [<?=$time_string_gas_C2H5OH?>],
                datasets: [{
                    label: "ぬか床・C2H5OH",
                    backgroundColor: "#8A2BE2",
                    borderColor: "#8A2BE2",
                    data: [<?=$data_string_gas_C2H5OH?>],
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
        var config_gas_CO = {
            type: 'line',
            data: {
                labels: [<?=$time_string_gas_CO?>],
                datasets: [{
                    label: "ぬか床・CO",
                    backgroundColor: "#9400D3",
                    borderColor: "#9400D3",
                    data: [<?=$data_string_gas_CO?>],
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
        var config_gas_C3H8 = {
            type: 'line',
            data: {
                labels: [<?=$time_string_gas_C3H8?>],
                datasets: [{
                    label: "ぬか床・C3H8",
                    backgroundColor: "#8B008B",
                    borderColor: "#8B008B",
                    data: [<?=$data_string_gas_C3H8?>],
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
        var config_gas_H2 = {
            type: 'line',
            data: {
                labels: [<?=$time_string_gas_H2?>],
                datasets: [{
                    label: "ぬか床・H2",
                    backgroundColor: "#9370DB",
                    borderColor: "#9370DB",
                    data: [<?=$data_string_gas_H2?>],
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

		<!-- PH -->
		<div style="width: 45%;display: inline-block;">
			<canvas id="canvas_ph" width="45%" height="33%"></canvas>
			<form action="export_csv.php" method="post">
				<input type="hidden" name="time_mode" value="<?=$timeMode?>">
				<input type="hidden" name="value_type" value="ph">
				<input type="hidden" name="export_array" value="<?php echo htmlentities(serialize($phArray)); ?>" />
				<div style="text-align: center;">
					<button class="btn btn-info"type="submit">ph data csv</button>
				</div>
			</form>
			<br />
		</div>
		
		<!-- Cond -->
		<div style="width: 45%;display: inline-block;">
			<canvas id="canvas_conductivity" width="45%" height="33%"></canvas>
			<form action="export_csv.php" method="post">
				<input type="hidden" name="time_mode" value="<?=$timeMode?>">
				<input type="hidden" name="value_type" value="conductivity">
				<input type="hidden" name="export_array" value="<?php echo htmlentities(serialize($conductivityArray)); ?>" />
				<div style="text-align: center;">
					<button class="btn btn-info"type="submit">conductivity data csv</button>
				</div>
			</form>
			<br />
		</div>
	

		<!-- TEMP SOIL -->
		<div style="width: 45%;display: inline-block;">
		<canvas id="canvas_temperature_soil" width="45%" height="33%"></canvas>
			<form action="export_csv.php" method="post">
				<input type="hidden" name="time_mode" value="<?=$timeMode?>">
				<input type="hidden" name="value_type" value="temperature_soil">
				<input type="hidden" name="export_array" value="<?php echo htmlentities(serialize($temperature_soilArray)); ?>" />
				<div style="text-align: center;">
					<button class="btn btn-info"type="submit">temperature_soil data csv</button>
				</div>
			</form>
			<br />
		</div>

		<!-- TEMP EXT -->
		<div style="width: 45%;display: inline-block;">
		<canvas id="canvas_temperature_external" width="45%" height="33%"></canvas>
			<form action="export_csv.php" method="post">
				<input type="hidden" name="time_mode" value="<?=$timeMode?>">
				<input type="hidden" name="value_type" value="temperature_external">
				<input type="hidden" name="export_array" value="<?php echo htmlentities(serialize($temperature_externalArray)); ?>" />
				<div style="text-align: center;">
					<button class="btn btn-info"type="submit">temperature_external data csv</button>
				</div>
			</form>
			<br />
		</div>

		<!-- MOISTURE SOIL -->
		<div style="width: 45%;display: inline-block;">
		<canvas id="canvas_moisture_internal" width="45%" height="33%"></canvas>
			<form action="export_csv.php" method="post">
				<input type="hidden" name="time_mode" value="<?=$timeMode?>">
				<input type="hidden" name="value_type" value="internal_moisture">
				<input type="hidden" name="export_array" value="<?php echo htmlentities(serialize($moistureArray)); ?>" />
				<div style="text-align: center;">
					<button class="btn btn-info"type="submit">internal moisture data csv</button>
				</div>
			</form>
			<br />
		</div>

		<!-- HUMIDITY EXT -->
		<div style="width: 45%;display: inline-block;">
		<canvas id="canvas_humidity_external" width="45%" height="33%"></canvas>
			<form action="export_csv.php" method="post">
				<input type="hidden" name="time_mode" value="<?=$timeMode?>">
				<input type="hidden" name="value_type" value="external_humidity">
				<input type="hidden" name="export_array" value="<?php echo htmlentities(serialize($humidityArray)); ?>" />
				<div style="text-align: center;">
					<button class="btn btn-info"type="submit">external humidity data csv</button>
				</div>
			</form>
			<br />
		</div>

		<!-- GAS1 -->
		<div style="width: 45%;display: inline-block;">
		<canvas id="canvas_gas_NH3" width="45%" height="33%"></canvas>
			<form action="export_csv.php" method="post">
				<input type="hidden" name="time_mode" value="<?=$timeMode?>">
				<input type="hidden" name="value_type" value="gas_NH3">
				<input type="hidden" name="export_array" value="<?php echo htmlentities(serialize($gas_NH3Array)); ?>" />
				<div style="text-align: center;">
					<button class="btn btn-info"type="submit">gaz (NH3) data csv</button>
				</div>
			</form>
			<br />
		</div>

		<!-- GAS2 -->
		<div style="width: 45%;display: inline-block;">
		<canvas id="canvas_gas_CH4" width="45%" height="33%"></canvas>
			<form action="export_csv.php" method="post">
				<input type="hidden" name="time_mode" value="<?=$timeMode?>">
				<input type="hidden" name="value_type" value="gas_CH4">
				<input type="hidden" name="export_array" value="<?php echo htmlentities(serialize($gas_CH4Array)); ?>" />
				<div style="text-align: center;">
					<button class="btn btn-info"type="submit">gaz (CH4) data csv</button>
				</div>
			</form>
			<br />
		</div>

		<!-- GAS3 -->
		<div style="width: 45%;display: inline-block;">
		<canvas id="canvas_gas_C4H10" width="45%" height="33%"></canvas>
			<form action="export_csv.php" method="post">
				<input type="hidden" name="time_mode" value="<?=$timeMode?>">
				<input type="hidden" name="value_type" value="gas_C4H10">
				<input type="hidden" name="export_array" value="<?php echo htmlentities(serialize($gas_C4H10Array)); ?>" />
				<div style="text-align: center;">
					<button class="btn btn-info"type="submit">gaz (C4H10) data csv</button>
				</div>
			</form>
			<br />
		</div>

		<!-- GAS4 -->
		<div style="width: 45%;display: inline-block;">
		<canvas id="canvas_gas_NO2" width="45%" height="33%"></canvas>
			<form action="export_csv.php" method="post">
				<input type="hidden" name="time_mode" value="<?=$timeMode?>">
				<input type="hidden" name="value_type" value="gas_NO2">
				<input type="hidden" name="export_array" value="<?php echo htmlentities(serialize($gas_NO2Array)); ?>" />
				<div style="text-align: center;">
					<button class="btn btn-info"type="submit">gaz (NO2) data csv</button>
				</div>
			</form>
			<br />
		</div>

		<!-- GAS5 -->
		<div style="width: 45%;display: inline-block;">
		<canvas id="canvas_gas_C2H5OH" width="45%" height="33%"></canvas>
			<form action="export_csv.php" method="post">
				<input type="hidden" name="time_mode" value="<?=$timeMode?>">
				<input type="hidden" name="value_type" value="gas_C2H5OH">
				<input type="hidden" name="export_array" value="<?php echo htmlentities(serialize($gas_C2H5OHArray)); ?>" />
				<div style="text-align: center;">
					<button class="btn btn-info"type="submit">gaz (C2H5OH) data csv</button>
				</div>
			</form>
			<br />
		</div>

		<!-- GAS6 -->
		<div style="width: 45%;display: inline-block;">
		<canvas id="canvas_gas_CO" width="45%" height="33%"></canvas>
			<form action="export_csv.php" method="post">
				<input type="hidden" name="time_mode" value="<?=$timeMode?>">
				<input type="hidden" name="value_type" value="gas_CO">
				<input type="hidden" name="export_array" value="<?php echo htmlentities(serialize($gas_COArray)); ?>" />
				<div style="text-align: center;">
					<button class="btn btn-info"type="submit">gaz (CO) data csv</button>
				</div>
			</form>
			<br />
		</div>

		<!-- GAS7 -->
		<div style="width: 45%;display: inline-block;">
		<canvas id="canvas_gas_C3H8" width="45%" height="33%"></canvas>
			<form action="export_csv.php" method="post">
				<input type="hidden" name="time_mode" value="<?=$timeMode?>">
				<input type="hidden" name="value_type" value="gas_C3H8">
				<input type="hidden" name="export_array" value="<?php echo htmlentities(serialize($gas_C3H8Array)); ?>" />
				<div style="text-align: center;">
					<button class="btn btn-info"type="submit">gaz (C3H8) data csv</button>
				</div>
			</form>
			<br />
		</div>
		
		<!-- GAS8 -->
		<div style="width: 45%;display: inline-block;">
		<canvas id="canvas_gas_H2" width="45%" height="33%"></canvas>
			<form action="export_csv.php" method="post">
				<input type="hidden" name="time_mode" value="<?=$timeMode?>">
				<input type="hidden" name="value_type" value="gas_H2">
				<input type="hidden" name="export_array" value="<?php echo htmlentities(serialize($gas_H2Array)); ?>" />
				<div style="text-align: center;">
					<button class="btn btn-info"type="submit">gaz (H2) data csv</button>
				</div>
			</form>
			<br />
		</div>
		
		<!-- GAS9 -->
		<div style="width: 45%;display: inline-block;">
		<canvas id="canvas14" width="45%" height="33%"></canvas>

			<div style="clear:both;"></div>


	<script>
        window.onload = function() {
            var ph = document.getElementById("canvas_ph").getContext("2d");
            window.myPh = new Chart(ph, config_ph);

            var cond = document.getElementById("canvas_conductivity").getContext("2d");
            window.myCond = new Chart(cond, config_cond);

            var tempSoil = document.getElementById("canvas_temperature_soil").getContext("2d");
            window.myTempSoil = new Chart(tempSoil, config_temp_soil);

            var tempExt = document.getElementById("canvas_temperature_external").getContext("2d");
            window.myTempExt = new Chart(tempExt, config_temp_ext);

            var moisture = document.getElementById("canvas_moisture_internal").getContext("2d");
            window.myMoisture = new Chart(moisture, config_moisture_internal);

            var humidity = document.getElementById("canvas_humidity_external").getContext("2d");
            window.myHumidity = new Chart(humidity, config_humidity_external);

            var gas_NH3 = document.getElementById("canvas_gas_NH3").getContext("2d");
            window.myGasNH3 = new Chart(gas_NH3, config_gas_NH3);

            var gas_CH4 = document.getElementById("canvas_gas_CH4").getContext("2d");
            window.myGasCH4 = new Chart(gas_CH4, config_gas_CH4);

            var gas_C4H10 = document.getElementById("canvas_gas_C4H10").getContext("2d");
            window.myGasC4H10 = new Chart(gas_C4H10, config_C4H10);

            var gas_NO2 = document.getElementById("canvas_gas_NO2").getContext("2d");
            window.myGasNO2 = new Chart(gas_NO2, config_gas_NO2);

            var gas_C2H5OH = document.getElementById("canvas_gas_C2H5OH").getContext("2d");
            window.myGasC2H5OH = new Chart(gas_C2H5OH, config_gas_C2H5OH);

            var gas_CO = document.getElementById("canvas_gas_CO").getContext("2d");
            window.myGasCO = new Chart(gas_CO, config_gas_CO);

            var gas_C3H8 = document.getElementById("canvas_gas_C3H8").getContext("2d");
            window.myGasC3H8 = new Chart(gas_C3H8, config_gas_C3H8);

            var gas_H2 = document.getElementById("canvas_gas_H2").getContext("2d");
            window.myGasH2 = new Chart(gas_H2, config_gas_H2);
        };
        var colorNames = Object.keys(window.chartColors);		
	</script>


	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>


	</body>
	
</html>