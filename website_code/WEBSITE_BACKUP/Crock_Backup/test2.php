
<html>

<head>
<h1 align="center">Traffic Monitoring Project</h1>
</head>

<body>

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

  <title>Traffic Monitoring Project</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
  
  <div class="container">
		<ul class="nav nav-tabs">
		<li class="active"><a data-toggle="tab" href="#home">HOME</a></li>
		<li class="dropdown">
		<a class="dropdown-toggle" data-toggle="dropdown" href="#">SERVICES <span class="caret"></span></a>
		<ul class="dropdown-menu">
			<li><a data-toggle="tab" href="CurrentTraffic.jsp">Current Traffic</a></li>
			<li><a data-toggle="tab" href="#trafficbyzipcodeandtime">Traffic Prediction</a></li> 
			<li><a data-toggle="tab" href="#navigation">Navigation</a></li> 
			<li><a data-toggle="tab" href="#restaurants">Restaurants</a></li> 
			<li><a data-toggle="tab" href="#parking">Parking</a></li>
			<li><a data-toggle="tab" href="#alternatetransport">Alternate Transport</a></li>
		</ul>
		</li>
		<li><a data-toggle="tab" href="#menu2">FEEDBACK</a></li>
		<li><a data-toggle="tab" href="#menu3">ABOUT US</a></li>
		</ul>
	
	<div class="tab-content">
    <div id="home" class="tab-pane fade in active">
      <h3>Home</h3>
      <p>Welcome to the traffic monitoring website!</p>
	  <div id="map"></div>
	  <style>
      #map 
      {
        width: 1100px;
        height: 650px;
		background-color: #CCC;
      }
	  </style>
	  
	  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyApUGdIzXiSr5bUX45d-P01gKVGA-9jiKg&signed_in=true&callback=initialize"></script>
	  <script> 
	  var longitudearray = <?php echo json_encode($longitude) ?>;
	  var latitudearray = <?php echo json_encode($latitude) ?>;
	  var description = <?php echo json_encode($description) ?>;

	  var coordinates = [];

	  for (var i = 0; i < longitudearray.length; i++)
	  {
	  	coordinates.push({});
	  }

	  for( var j = 0; j < longitudearray.length; j++)
	  {
	  	coordinates[j].lat = latitudearray[j];
	  	coordinates[j].lng = longitudearray[j];
	  }
	  
	  function initialize() 
	  {
		  var myLatLng = {lat: 40.4867, lng: -74.444};
		  var map = new google.maps.Map(document.getElementById('map'), {zoom: 12,center: new google.maps.LatLng(myLatLng.lat, myLatLng.lng),mapTypeId: google.maps.MapTypeId.ROADMAP});
		  var geocoder = new google.maps.Geocoder();

		  document.getElementById('submit').addEventListener('click', function() {geocodeAddress(geocoder, map);});

		  var latlong2 = {lat:41.10265,lng:-74.028205};

		  drawmarkers(map);
  
		  
	   }

		function geocodeAddress(geocoder, resultsMap) 
		{
		  var address = document.getElementById('address').value;
		  geocoder.geocode({'address': address}, function(results, status) {
		    if (status === google.maps.GeocoderStatus.OK) 
		    {
		      resultsMap.setCenter(results[0].geometry.location);
		      var marker = new google.maps.Marker({map: resultsMap,position: results[0].geometry.location});
		    } 
		    else 
		    {
		      alert('Geocode was not successful for the following reason: ' + status);
		    }
		  });
		}

		  function drawmarkers(map)
		  {  
			  for(var j = 0; j< coordinates.length ; j++)
			  {
					
				markernew = new google.maps.Marker({
				    map: map,
					position: new google.maps.LatLng(coordinates[j].lat,coordinates[j].lng),
					title: description[j]
	   			});

			  }

			  markernew.setMap(map);
		  }
      
      google.maps.event.addDomListener(window, 'load', initialize);
      
	  </script>
	  
	  
    </div>
	
	<div id="trafficbyzipcodeandtime" class="tab-pane fade">
	<h3>predicted traffic</h3>
	</div>
	
	<div id="restaurants" class="tab-pane fade">
	<h3>restaurants</h3>
	</div>
	
	<div id="parking" class="tab-pane fade">
	<h3>parking</h3>
	</div>
	
	<div id="alternatetransport" class="tab-pane fade">
	<h3>Alternate Transport</h3>
	</div>
	
	<div id="navigation" class="tab-pane fade">
	<h3>navigation</h3>
	</div>
	
	<div id="b" class="tab-pane fade">
	<h3>hello</h3>
	</div>
	<div id="menu2" class="tab-pane fade">
      <h3>FEEDBACK</h3>
      <p>Help us Improve!</p>
	  <form>
	  <textarea id="id_feedback" rows="10" cols="40" name="feedback"></textarea></p>
	  <br><br>
	  <input type="submit" value="Submit"> 
      </form>   
    </div>
    
	<div id="menu3" class="tab-pane fade">
      <h3>GROUP MEMBERS</h3>
      <p>1. Kush Patel</p>
	  <p>2. Tejas Ravi</p>
      <p>3. Jeff Gillen</p>
      <p>4. Shreyas Bhandare</p>
      <p>5. Kisholoy Vinayak Ghosh</p>
      <p>6. Aaditya Shukla</p>
    </div>
  
	</div>
	
	<div id="floating-panel">
		<br>
      <input id="address" type="textbox" value="New Brunswick, NJ">
      <input id="submit" type="button" value="Geocode">
      <input id="Accidents" type="button" value="Plot Accidents">
      <br>
    </div>
	
	<br>
	
	
	<footer> 
	&copy 2015 Group 7
	</footer>
	
</div>

</body>

	
</html>



