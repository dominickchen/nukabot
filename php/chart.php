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
		
		<h1>Nuka Stats</h1>
		

<canvas id="canvas1" width="75%"></canvas>

<div style="clear:both;"></div>

<canvas id="canvas2" width="75%"></canvas>


    <script>
        var MONTHS = ["2017/12/15 16:00", "2017/12/15 15:00", "2017/12/15 15:00", "2017/12/15 15:00", "2017/12/15 15:00", "2017/12/15 15:00", "2017/12/15 7:00", "2017/12/15 8:00", "2017/12/15 9:00", "2017/12/15 10:00", "2017/12/15 11:00", "2017/12/15 12:00"];
        var config1 = {
            type: 'line',
            data: {
                labels: ["2017/12/15 6:00", "2017/12/15 7:00", "2017/12/15 8:00", "2017/12/15 9:00", "2017/12/15 10:00", "2017/12/15 11:00", "2017/12/15 12:00"],
                datasets: [{
                    label: "Nuka Temperature",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [
                        15.6,
                        15.9,
                        16.4,
                        17.1,
                        18.6,
                        18.3,
                        17.6,                        
                    ],
                    fill: false,
                }, {
                    label: "Nuka Humidity",
                    fill: false,
                    backgroundColor: window.chartColors.blue,
                    borderColor: window.chartColors.blue,
                    data: [
                        30.9,
                        35.9,
                        41.9,
                        47.9,
                        48.5,
                        44.3,
                        43.2,                    
                    ],
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'Nuka Temp&Humidity'
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



        var colorNames = Object.keys(window.chartColors);
    </script>    		



    <script>
        var MONTHS = ["2017/12/15 16:00", "2017/12/15 15:00", "2017/12/15 15:00", "2017/12/15 15:00", "2017/12/15 15:00", "2017/12/15 15:00", "2017/12/15 7:00", "2017/12/15 8:00", "2017/12/15 9:00", "2017/12/15 10:00", "2017/12/15 11:00", "2017/12/15 12:00"];
        var config2 = {
            type: 'line',
            data: {
                labels: ["2017/12/15 6:00", "2017/12/15 7:00", "2017/12/15 8:00", "2017/12/15 9:00", "2017/12/15 10:00", "2017/12/15 11:00", "2017/12/15 12:00"],
                datasets: [{
                    label: "Nuka pH",
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [
                        4.3,
                        4.4,
                        4.4,
                        4.4,
                        4.5,
                        4.4,
                        4.4,
                    ],
                    fill: false,
                }]
            },
            options: {
                responsive: true,
                title:{
                    display:true,
                    text:'Nuka pH'
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

        window.onload = function() {
            var ctx = document.getElementById("canvas1").getContext("2d");
            window.myLine = new Chart(ctx, config1);
            var ph = document.getElementById("canvas2").getContext("2d");
            window.myPh = new Chart(ph, config2);
        };



        var colorNames = Object.keys(window.chartColors);
    </script>    		


	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js"></script>


	</body>
	
</html>