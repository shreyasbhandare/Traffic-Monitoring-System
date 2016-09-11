<?php 

session_start();

$hourdata = array();
$mondayhourdata = array();
$tuesdayhourdata = array();
$wednesdayhourdata = array();
$fridayhourdata = array();


if(isset($_POST['submit']))
{
	getchartdata();
}

function gethourlydata($hour,$zipcode,$day)
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
	
	
	$sql = "SELECT AVG(`Severity`) FROM `livecongestion` WHERE `ZipCode` =\"" .$zipcode. "\" and `Time`=\"" .$hour. ":00:00\" and `Day` = \"" .$day."\"";
	$result = $conn->query($sql);
	
	//echo $sql;
	$time;
	$temp = 0;

	if ($result->num_rows > 0)
	{
		// output data of each row
		while($row = $result->fetch_assoc())
		{
			$time = $row["AVG(`Severity`)"];
			if($time == NULL)
			{
				return 0;
			}
		}
	} else
	{	
	}
	
	return $time;
	
	
	
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
		$hourdata[$var] = gethourlydata($hours[$var], $zipcode, "Thursday");
		$fridayhourdata[$var] = gethourlydata($hours[$var], $zipcode, "Friday");
		$tuesdayhourdata[$var] = gethourlydata($hours[$var], $zipcode, "Tuesday");
		$wednesdayhourdata[$var] = gethourlydata($hours[$var], $zipcode, "Wednesday");
		$mondayhourdata[$var] = gethourlydata($hours[$var], $zipcode, "Monday");

	}
	
	
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
      
      <form action="Charts.php" method = "post" role="form">
  			<input id="zipcode" name="zipcode" type="textbox" value="08901">
  			<input type="submit" name="submit">
	  </form>
	  </div>

	  

<div id="container" style="position: absolute; top:280px; width:100%; height:400px;"></div>
<script>


$(function () 
		{  
			$('#container').highcharts({
		        chart: {
		            type: 'line'
		        },
		        title: {
		            text: 'Average Severity for Zip Code'
		        },
		        xAxis: {
		            categories: ['08:00 am','09:00 am','10:00 am','11:00 am','12:00 noon','013:00 noon', '14:00 noon', '15:00 am', '16:00 even', '17:00 even', '18:00 even', '19:00 even', '20:00 even']
		        },
		        yAxis: {
		            title: {
		                text: 'Severity'
		            }
		        },
		        series: [{
					name: 'Monday',
		            data: [<?php global $hourdata; echo join($mondayhourdata, ',') ?>]
				},{
					name: 'Tuesday',
		            data: [<?php global $tuesdayhourdata;echo join($tuesdayhourdata, ',') ?>]
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
		});
</script>


<p><a href="Charts2.php">Accident  and Police Reports Trending</a></p>
<p><a href="Home3.php"> Home</a></p>


	<DIV style="position: absolute; top:925px">
	<footer> 
	&copy 2015 Group 7
	</footer>
	</DIV>
 
</body>

	
</html>