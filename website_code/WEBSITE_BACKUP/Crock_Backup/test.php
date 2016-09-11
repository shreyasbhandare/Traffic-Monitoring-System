<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>

<head>
<h1 align="center">Traffic Monitoring Project</h1>
</head>

<body>

		Current Traffic Here
		
<?php 

$servername = "localhost";
$username = "kush";
$password = "password";
$dbname = "trafficprediction";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT `Latitude`,`Longitude`,`Description` FROM `livetraffic` WHERE `Type` = \"Accident\"";
$result = $conn->query($sql);

$i = 0;
$latitude = array();
$longitude = array();
$description = array();


if ($result->num_rows > 0) {
	// output data of each row
	while($row = $result->fetch_assoc()) 
	{
		$latitude[] = $row["Latitude"];
		$longitude[] = $row["Longitude"];
		$description[] = $row["Description"];
		
	}
} else {
	echo "0 results";
}

/*

for($x = 0; $x < count($latitude) ; $x++)
{
	echo $latitude[$x]; 
	echo "<br>";
	echo $longitude[$x];
	echo "<br>";
	echo $description[$x];
	echo "<br>";
}
*/

$conn->close();

		
?>

<script type="text/javascript">

var longitudearray = <?php echo json_encode($longitude) ?>;
var latitudearray = <?php echo json_encode($latitude) ?>;
var description = <?php echo json_encode($description) ?>;

document.write( longitudearray[1] ); // false

for (var i = 0; i < latitudearray.length; i++) 
{
  document.write("Latitude: " + latitudearray[i]);
  document.write("<br>");
  document.write("Longitude: " + longitudearray[i]);
  document.write("<br>");
  document.write("Description: " + description[i]);
  document.write("<br>");
  document.write("<br>");
  document.write("<br>");
  
}

var coordinates = [];

for (var i = 0; i < longitudearray.length; i++)
{
	coordinates.push({});
}

var obj;

for( var j = 0; j < longitudearray.length; j++)
{
	coordinates[j].latitude = latitudearray[j];
	coordinates[j].longitude = longitudearray[j];
}

for( var j = 0; j < longitudearray.length; j++)
{
	console.log(coordinates[j]);
}



</script>

</body>


	
</html>



