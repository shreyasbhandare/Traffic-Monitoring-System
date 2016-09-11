<?php 

session_start();

$hourdata = array();
$mondayhourdata = array();
$tuesdayhourdata = array();
$wednesdayhourdata = array();
$fridayhourdata = array();

$policehourdata = array();;
$policemondayhourdata = array();
$policetuesdayhourdata = array();;
$policewednesdayhourdata = array();;
$policefridayhourdata = array();;


if(isset($_POST['submit']))
{
	getchartdata();
	getpolicedata();
}

function gethourlydata($hour,$zipcode, $type, $day)
{
	
	$host="localhost"; // Host name
	$username="kush"; // Mysql username
	$password="password"; // Mysql password
	$db_name="trafficprediction"; // Database name
	
	// Create connection
	$conn = new mysqli($host, $username, $password, $db_name);
	// Check connection
	if ($conn->connect_error)
	{
		die("Connection failed: " . $conn->connect_error);
	}
	
	$sql = "SELECT `incidenttype` FROM `historicwazealerts` WHERE `ZipCode` =\"" .$zipcode. "\" and `Time`=\"" .$hour. ":00:00\" and `Day` = \"" .$day."\""." and `incidenttype` = \"" .$type."\"";
	$result = $conn->query($sql);
	//echo $sql;
	
	//echo $sql;
	$time;
	$temp = 0;
	
	$num_rows = $result->num_rows;

	if ($result->num_rows > 0)
	{
		// output data of each row
		while($row = $result->fetch_assoc())
		{
			$time = $row["incidenttype"];
			if($time == NULL)
			{
				return 0;
			}
		}
	} else
	{	
	}
	
	return $num_rows;
}

function getchartdata()
{
	$zipcode=$_POST['zipcode'];
	
	global $hourdata;
	global $mondayhourdata;
	global $tuesdayhourdata;
	global $wednesdayhourdata;
	global $fridayhourdata;
	
	$hours = array();
	$hours[0] = "8";
	$hours[1] = "9";
	$hours[2] = "10";
	$hours[3] = "11";
	$hours[4] = "12";
	$hours[5] = "13";
	$hours[6] = "14";
	$hours[7] = "15";
	$hours[8] = "16";
	$hours[9] = "17";
	$hours[10] = "18";
	$hours[11] = "19";
	$hours[12] = "20";
	
	
	for($var = 0; $var < 13 ; $var++)
	{
			$hourdata[$var] = gethourlydata($hours[$var], $zipcode, "ACCIDENT", "Thrusday");;
			$fridayhourdata[$var] = gethourlydata($hours[$var], $zipcode, "ACCIDENT", "Friday");;
			$tuesdayhourdata[$var] = gethourlydata($hours[$var], $zipcode, "ACCIDENT" , "Tuesday");;
			$wednesdayhourdata[$var] = gethourlydata($hours[$var], $zipcode, "ACCIDENT", "Wednesday");;
			$mondayhourdata[$var] = gethourlydata($hours[$var], $zipcode, "ACCIDENT", "Monday");;
	
	}
	
	//print_r($hourdata);
}

function getpolicedata()
{
	$zipcode=$_POST['zipcode'];

	global $policehourdata;
	global $policemondayhourdata;
	global $policetuesdayhourdata;
	global $policewednesdayhourdata;
	global $policefridayhourdata;

	$hours = array();
	$hours[0] = "8";
	$hours[1] = "9";
	$hours[2] = "10";
	$hours[3] = "11";
	$hours[4] = "12";
	$hours[5] = "13";
	$hours[6] = "14";
	$hours[7] = "15";
	$hours[8] = "16";
	$hours[9] = "17";
	$hours[10] = "18";
	$hours[11] = "19";
	$hours[12] = "20";
	
	for($var = 0; $var < 13 ; $var++)
	{
		$policehourdata[$var] = gethourlydata($hours[$var], $zipcode, "POLICE", "Thursday");
		$policemondayhourdata[$var] = gethourlydata($hours[$var], $zipcode, "POLICE", "Monday");
		$policetuesdayhourdata[$var] = gethourlydata($hours[$var], $zipcode, "POLICE", "Tuesday");
		$policewednesdayhourdata[$var] = gethourlydata($hours[$var], $zipcode, "POLICE", "Wednesday");
		$policefridayhourdata[$var] = gethourlydata($hours[$var], $zipcode, "POLICE", "Friday");
	
	}


	//print_r($hourdata);
}


?>



<html>

<head>
<h1 align="center">Traffic Monitoring Project</h1>
<style>
</style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script src="http://code.highcharts.com/highcharts.js"></script>
</head>

<body>
 
  <title>Traffic Monitoring Project</title>
<div style="position: absolute; top:150px; left:50px">
<p>Please enter the zip code of the area to display the traffic trend</p>
      
      <form action="Charts2.php" method = "post" role="form">
  			<input id="zipcode" name="zipcode" type="textbox" value="08876">
  			
  			
  			<input type="submit" name="submit">
	  </form>
	  </div>

	  

<div id="container" style="position: absolute; top:280px; width:100%; height:200px;"></div>
<div class="spacer" style = "height: 20px;"></div>
<div id="container2" style="position: absolute; top:600px; width:100%; height:200px;"></div>
<script>


$(function () 
		{  
			$('#container').highcharts({
		        chart: {
		            type: 'line'
		        },
		        title: {
		            text: 'Accident Reports (Count)'
		        },
		        xAxis: {
		            categories: ['08:00 am','09:00 am','10:00 am','11:00 am','12:00 noon','013:00 noon', '14:00 noon', '15:00 am', '16:00 even', '17:00 even', '18:00 even', '19:00 even', '20:00 even']
		        },
		        yAxis: {
		            title: {
		                text: 'Accident Reports'
		            }
		        },
		        series: [{
					name: 'Monday',
		            data: [<?php global $mondayhourdata; echo join($mondayhourdata, ',') ?>]
				},{
					name: 'Tuesday',
		            data: [<?php global $tuesdayhourdata; echo join($tuesdayhourdata, ',') ?>]
				}, {
					name: 'Wednesday',
		            data: [<?php global $wednesdayhourdata; echo join($wednesdayhourdata, ',') ?>]
				}, {
					name: 'Thursday',
		            data: [<?php global $hourdata; echo join($hourdata, ',') ?>]
				},
				{
					name: 'Friday',
		            data: [<?php global $fridayhourdata; echo join($fridayhourdata, ',') ?>]
				}]
		    });

			$('#container2').highcharts({
		        chart: {
		            type: 'line'
		        },
		        title: {
		            text: 'Police Reports (Count)'
		        },
		        xAxis: {
		            categories: ['08:00 am','09:00 am','10:00 am','11:00 am','12:00 noon','013:00 noon', '14:00 noon', '15:00 am', '16:00 even', '17:00 even', '18:00 even', '19:00 even', '20:00 even']
		        },
		        yAxis: {
		            title: {
		                text: 'Police Reports'
		            }
		        },
		        series: [{
					name: 'Monday',
		            data: [<?php global $policemondayhourdata ; echo join($policemondayhourdata, ',') ?>]
				},{
					name: 'Tuesday',
		            data: [<?php global $policetuesdayhourdata ; echo join($policetuesdayhourdata, ',') ?>]
				}, {
					name: 'Wednesday',
		            data: [<?php global $policewednesdayhourdata ; echo join($policewednesdayhourdata, ',') ?>]
				}, {
					name: 'Thursday',
		            data: [<?php global $policehourdata ; echo join($policehourdata, ',') ?>]
				},
				{
					name: 'Friday',
		            data: [<?php global $policefridayhourdata ; echo join($policefridayhourdata, ',') ?>]
				}]
		    }

			

		    );

			
		    
		});


</script>


<p><a href="Chatstemp.php">Severity Trending</a></p>
<p><a href="Home3.php"> Home</a></p>


	<DIV style="position: absolute; top:925px">
	<footer> 
	&copy 2015 Group 7
	</footer>
	</DIV>
 
</body>

	
</html>