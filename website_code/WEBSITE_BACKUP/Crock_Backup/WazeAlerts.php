
<html>

<head>
<h1 align="center">Traffic Monitoring Project</h1>
</head>

<body>

<?php 

session_start();

function createarray($incidenttype)
{
	$servername = "localhost";
	$username = "kush";
	$password = "password";
	$dbname = "trafficprediction";
	$tablename = "livewazealerts";
	$alerttype;
	
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) 
	{
		die("Connection failed: " . $conn->connect_error);
	}
	
	if($incidenttype == "Accidents")
	{
		$alerttype = "ACCIDENT";
	}
	elseif($incidenttype == "Construction")
	{
		$alerttype = "ROAD_CLOSED";
	}
	else
	{
		$alerttype = "POLICE";
	}
	
	
	$sql = "SELECT `latitude`,`longitude`,`numofThumbs` FROM `" . $tablename . "` WHERE `incidenttype` =\"" .$alerttype . "\"" ;
	$result = $conn->query($sql);
	
	$i = 0;
	$latitude = array();
	$longitude = array();
	$description = array();

	
	if ($result->num_rows > 0) 
	{
		// output data of each row
		while($row = $result->fetch_assoc())
		{
			$latitude[] = $row["latitude"];
			$longitude[] = $row["longitude"];
			$description[] = $row["numofThumbs"];
		}
	} else 
	{
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
	
	return [$latitude,$longitude,$description];

}

//Below array for accidents and congestion
$latitude = array();
$longitude = array();
$description = array();
list($latitude,$longitude,$description) = createarray("Accidents");

//Below array for construction
$conslatitude = array();
$conslongitude = array();
$consdescription = array();
list($conslatitude,$conslongitude,$consdescription) = createarray("Construction");


//Below array for construction
$eventlatitude = array();
$eventlongitude = array();
$eventdescription = array();
list($eventlatitude,$eventlongitude,$eventdescription) = createarray("Events");

		
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
      <p>Welcome to the traffic monitoring website!<h2>
      <?php 
                                        if (isset($_SESSION["firstname"])) {
										$NAME = $_SESSION["firstname"];
										
											echo $NAME;
											echo "<br> Your Home ZipCode:";
											echo $_SESSION["ZipCode"];
				
										}										
										?></h2></p>
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

	  // Arrays for Congestion and Accidents
	  var longitudearray = <?php echo json_encode($longitude) ?>;
	  var latitudearray = <?php echo json_encode($latitude) ?>;
	  var description = <?php echo json_encode($description) ?>;

	  // Arrays for Construction
	  
	  var conslongitudearray = <?php echo json_encode($conslongitude) ?>;
	  var conslatitudearray = <?php echo json_encode($conslatitude) ?>;
	  var consdescription = <?php echo json_encode($consdescription) ?>;
	  
	  // Arrays for Special Events
	  var eventlongitudearray = <?php echo json_encode($eventlongitude) ?>;
	  var eventlatitudearray = <?php echo json_encode($eventlatitude) ?>;
	  var eventdescription = <?php echo json_encode($eventdescription) ?>;
	  

	  var coordinates = [];
	  var conscoordinates = [];
	  var eventcoordinates = [];

	  
	  var incidentmarkers = [];
	  var consmarkers = [];
	  var eventmarkers = [];

	  // Create Objects Empty array (Traffic and Congestion Data Points) of object before populating
	  for (var i = 0; i < longitudearray.length; i++)
	  {
	  	coordinates.push({});
	  }

	  // Create Objects Empty array (Construction Data Points) of object before populating
	  for (var i = 0; i < conslongitudearray.length; i++)
	  {
	  	conscoordinates.push({});
	  }

	  // Create Objects Empty array (Events Data Points) of object before populating

	  for (var i = 0; i < eventlongitudearray.length; i++)
	  {
	  	eventcoordinates.push({});
	  }

	  
      // Populate the object array with individual data points form the single arrays
      
	  for( var j = 0; j < longitudearray.length; j++)
	  {
	  	coordinates[j].lat = latitudearray[j];
	  	coordinates[j].lng = longitudearray[j];
	  }

	  for( var j = 0; j < conslongitudearray.length; j++)
	  {
	  	conscoordinates[j].lat = conslatitudearray[j];
	  	conscoordinates[j].lng = conslongitudearray[j];
	  }

	  for( var j = 0; j < eventlongitudearray.length; j++)
	  {
	  	eventcoordinates[j].lat = eventlatitudearray[j];
	  	eventcoordinates[j].lng = eventlongitudearray[j];
	  }

	  
	  
	  function initialize() 
	  {
		  var myLatLng = {lat: 40.4867, lng: -74.444};
		  var map = new google.maps.Map(document.getElementById('map'), {zoom: 12,center: new google.maps.LatLng(myLatLng.lat, myLatLng.lng),mapTypeId: google.maps.MapTypeId.ROADMAP});
		  var geocoder = new google.maps.Geocoder();

		  document.getElementById('submit').addEventListener('click', function() {geocodeAddress(geocoder, map);});

		  document.getElementById('Accidents').addEventListener('click', function() {drawmarkers(map);});

		  document.getElementById('Construction').addEventListener('click', function() {drawconsmarkers(map);});

		  document.getElementById('Events').addEventListener('click', function() {draweventmarkers(map);});

		  document.getElementById('Clear').addEventListener('click', function() {clearmarkers();});


		  //drawmarkers(map);
  
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

		function clearmarkers(map)
		{
			toggleMarkers();
			toggleConsMarkers();
			toggleEventMarkers();

		}

		  function drawmarkers(map)
		  { 
			  //toggleEventMarkers();
			  //toggleConsMarkers();
			  for(var j = 0; j< coordinates.length ; j++)
			  {
					
				 markernew = new google.maps.Marker({
				    map: map,
					position: new google.maps.LatLng(coordinates[j].lat,coordinates[j].lng),
					title: description[j],
					icon: 'caraccident.png',
					animation: google.maps.Animation.DROP
	   			});

				 var content = description[j];   
                 var infowindow = new google.maps.InfoWindow();

				google.maps.event.addListener(markernew,'click', (function(markernew,content,infowindow){ 
				        return function() {
				           infowindow.setContent(content);
				           infowindow.open(map,markernew);
				        };
				    })(markernew,content,infowindow)); 

	   			incidentmarkers.push(markernew);

			  }

			  markernew.setMap(map);
		  }

		  function drawconsmarkers(map)
		  {  
			  //toggleMarkers();
			  //toggleEventMarkers();

			  for(var j = 0; j< conscoordinates.length ; j++)
			  {
					
				consmarkernew = new google.maps.Marker({
				    map: map,
					position: new google.maps.LatLng(conscoordinates[j].lat,conscoordinates[j].lng),
					title: consdescription[j],
					icon: 'caution.png',
					animation: google.maps.Animation.DROP
	   			});

				var content = consdescription[j];   
                var infowindow = new google.maps.InfoWindow();

				google.maps.event.addListener(consmarkernew,'click', (function(consmarkernew,content,infowindow){ 
				        return function() {
				           infowindow.setContent(content);
				           infowindow.open(map,consmarkernew);
				        };
				    })(consmarkernew,content,infowindow)); 
	   			

	   			consmarkers.push(consmarkernew);

			  }

			  consmarkernew.setMap(map);
		  }

		  function draweventmarkers(map)
		  {  
			 //toggleMarkers();
			 //toggleConsMarkers();
			  for(var j = 0; j< eventcoordinates.length ; j++)
			  {
					
				markernew = new google.maps.Marker({
				    map: map,
					position: new google.maps.LatLng(eventcoordinates[j].lat,eventcoordinates[j].lng),
					title: eventdescription[j],
					icon: 'police.png',
					animation: google.maps.Animation.DROP
	   			});

				var content = eventdescription[j];   
                var infowindow = new google.maps.InfoWindow();

				google.maps.event.addListener(markernew,'click', (function(markernew,content,infowindow){ 
				        return function() {
				           infowindow.setContent(content);
				           infowindow.open(map,markernew);
				        };
				    })(markernew,content,infowindow)); 
	   			

	   			eventmarkers.push(markernew);

			  }

			  markernew.setMap(map);
		  }

		  function toggleMarkers() {
			  for (i = 0; i < incidentmarkers.length; i++) {
			    if (incidentmarkers[i].getMap() != null) incidentmarkers[i].setMap(null);
			    else incidentmarkers[i].setMap(map);
			  }
			}

		  function toggleConsMarkers() {
			  for (i = 0; i < consmarkers.length; i++) {
			    if (consmarkers[i].getMap() != null) consmarkers[i].setMap(null);
			    else consmarkers[i].setMap(map);
			  }
			}

		  function toggleEventMarkers() {
			  for (i = 0; i < eventmarkers.length; i++) {
			    if (eventmarkers[i].getMap() != null) eventmarkers[i].setMap(null);
			    else eventmarkers[i].setMap(map);
			  }
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
      <input id="Construction" type="button" value="Plot Road Closed">
      <input id="Events" type="button" value="Plot Police">
      <input id="Clear" type="button" value="Clear Markers">
      <br>
    </div>
	
	<br>
	
	
	<footer> 
	&copy 2015 Group 7
	</footer>
	
</div>

</body>

	
</html>



