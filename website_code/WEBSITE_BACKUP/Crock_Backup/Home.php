<?php 

//session_start();

include('DataClass.php');

if(isset($_POST['submit']))
{
	getdata();
}

function getdata()
{
	$zipcode=$_POST['zipcode'];
	$dayofweek=$_POST['day'];
	$severity=$_POST['severity'];
	
	$policewazelatitude= array();
	$policewazelongitude = array();
	$policewazethumbsup = array();
	$policewazesubtype = array();
	
	
	$accidentewazelatitude= array();
	$addicentwazelongitude = array();
	$accidentwazethumbsup = array();
	$accidentwazesubtype = array();
	
	
	$hazardwazelatitude= array();
	$hazardwazelongitude = array();
	$hazardwazethumbsup = array();
	$hazardwazesubtype = array();
	
	
	$jamwazelatitude= array();
	$jamwazelongitude = array();
	$jamwazethumbsup = array();
	$jamwazesubtype = array();
	
	$roadclosedwazelatitude= array();
	$roadclosedwazelongitude = array();
	$roadclosedwazethumbsup = array();
	$roadclosedwazesubtype = array();
	
	
	$dataobject = new TrafficData();
	$dataobject->getdatabyseverity($dayofweek, $zipcode);
	$dataobject->getwazealerts($zipcode, "POLICE");
	
	$policewazelatitude=  $dataobject->getwazearray("LAT");
	$policewazelongitude = $dataobject->getwazearray("LONG");
	$policewazethumbsup = $dataobject->getwazearray("THUMBS");
	$policewazesubtype = $dataobject->getwazearray("SUBTYPE");
	
	
	$dataobject->getwazealerts($zipcode, "HAZARD");
	
	$hazardwazelatitude=  $dataobject->getwazearray("LAT");
	$hazardwazelongitude = $dataobject->getwazearray("LONG");
	$hazardwazethumbsup = $dataobject->getwazearray("THUMBS");
	$hazardwazesubtype = $dataobject->getwazearray("SUBTYPE");
	
	
	$dataobject->getwazealerts($zipcode, "JAM");
	
	$jamwazelatitude=  $dataobject->getwazearray("LAT");
	$jamwazelongitude = $dataobject->getwazearray("LONG");
	$jamwazethumbsup = $dataobject->getwazearray("THUMBS");
	$jamwazesubtype = $dataobject->getwazearray("SUBTYPE");
	
	$dataobject->getwazealerts($zipcode, "ROAD_CLOSED");
	
	$roadclosedwazelatitude=  $dataobject->getwazearray("LAT");
	$roadclosedwazelongitude = $dataobject->getwazearray("LONG");
	$roadclosedwazethumbsup = $dataobject->getwazearray("THUMBS");
	$roadclosedwazesubtype = $dataobject->getwazearray("SUBTYPE");
	
	$dataobject->getwazealerts($zipcode, "ACCIDENT");
	
	$accidentwazelatitude=  $dataobject->getwazearray("LAT");
	$accidentwazelongitude = $dataobject->getwazearray("LONG");
	$accidentwazethumbsup = $dataobject->getwazearray("THUMBS");
	$accidentwazesubtype = $dataobject->getwazearray("SUBTYPE");
	
	
	
	$startlatitude = array();
	$startlongitude = array();
	$endlatitude = array();
	$endlongitude = array();
	$delay = array();
	$accidenttype = array();
	
	$startlatitude = $dataobject->getarray("START_LAT");
	$startlongitude = $dataobject->getarray("START_LONG");
	$endlatitude = $dataobject->getarray("END_LAT");
	$endlongitude = $dataobject->getarray("END_LONG");
	$delay = $dataobject->getarray("DELAY");
	$accidenttype = $dataobject->getarray("TYPE");
	
}
		
?>

<html>

<head>
<h1 align="center">Traffic Monitoring Project</h1>

<style type="text/css"> 
#inputform{
    position:absolute;
    top: 50%;
    right: 5%;
    width:15em;
    height:40em;
    margin-top: -9em; /*set to a negative number 1/2 of your height*/
    margin-left: -15em; /*set to a negative number 1/2 of your width*/
    border: 1px solid #ccc;
    background-color: #f3f3f3;
}

</style>
</head>

<body>



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
											$userzip = $_SESSION["ZipCode"];
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
	  
	  
	  <script>
	  var userzip = <?php echo json_encode($userzip) ?>;
	  var myLatLng = {lat: 40.4867, lng: -74.444};

	  if (userzip == "08830")
	  {
		  myLatLng = {lat: 40.5710, lng: -74.3169};
	  }
	  else if (userzip == "08854")
	  {
		  myLatLng = {lat: 40.4867, lng: -74.4629};
	  }
	  else if (userzip == "08876")
	  {
		  myLatLng = {lat: 40.5554, lng: -74.6608};
	  }
	  else if (userzip == "08820")
	  {
		  myLatLng = {lat: 40.5776, lng: -74.3653};
	  }
	  else if (userzip == "07061")
	  {
		  myLatLng = {lat: 40.6339, lng: -74.4076};
	  }
	  else if (userzip == "08906")
	  {
		  myLatLng = {lat: 40.4894, lng: -74.4494};
	  }

	  </script>
	  
	  <script> 

	  // Arrays for Congestion
	  var startlongitudearray = <?php echo json_encode($startlongitude) ?>;
	  var startlatitudearray = <?php echo json_encode($startlatitude) ?>;
	  var endlongitudearray = <?php echo json_encode($endlongitude) ?>;
	  var endlatitudearray = <?php echo json_encode($endlatitude) ?>;
	  var delay = <?php echo json_encode($delay) ?>;
	  var accidenttype = <?php echo json_encode($accidenttype)?>;


	  var policewazelatitude= <?php echo json_encode($policewazelatitude) ?>;
	  var policewazelongitude = <?php echo json_encode($policewazelongitude) ?>;
	  var policewazethumbsup = <?php echo json_encode($policewazethumbsup) ?>;
	  var policewazesubtype = <?php echo json_encode($policewazesubtype) ?>;

	  var accidentwazelatitude= <?php echo json_encode($accidentwazelatitude) ?>;
	  var accidentwazelongitude = <?php echo json_encode($accidentwazelongitude) ?>;
	  var accidentwazethumbsup = <?php echo json_encode($accidentwazethumbsup) ?>;
	  var accidentwazesubtype = <?php echo json_encode($accidentwazesubtype) ?>;

	  console.log(accidentwazelatitude);
	  console.log(accidentwazelongitude;

	  var jamwazelatitude= <?php echo json_encode($jamwazelatitude) ?>;
	  var jamwazelongitude = <?php echo json_encode($jamwazelongitude) ?>;
	  var jamwazethumbsup = <?php echo json_encode($jamwazethumbsup) ?>;
	  var jamwazesubtype = <?php echo json_encode($jamwazesubtype) ?>;

	  var hazardwazelatitude= <?php echo json_encode($hazardwazelatitude) ?>;
	  var hazardwazelongitude = <?php echo json_encode($hazardwazelongitude) ?>;
	  var hazardthumbsup = <?php echo json_encode($hazardthumbsup) ?>;
	  var hazardwazesubtype = <?php echo json_encode($hazardwazesubtype) ?>;

	  var roadclosedwazelatitude= <?php echo json_encode($roadclosedwazelatitude) ?>;
	  var roadclosedwazelongitude = <?php echo json_encode($roadclosedwazelongitude) ?>;
	  var roadclosedwazethumbsup = <?php echo json_encode($roadclosedwazethumbsup) ?>;
	  var roadclosedwazesubtype = <?php echo json_encode($roadclosedwazesubtype) ?>;
	  

	  var startcoordinates = [];
	  var endcoordinates = [];

	  var policecoordinates = [];
	  var hazardcoordinates = [];
	  var accidentcoordinates = [];
	  var jamcoordinates = [];
	  var roadclosedcoordinates = [];
	  
	  // Create Objects Empty array (Traffic and Congestion Data Points) of object before populating
	  for (var i = 0; i < roadclosedwazelatitude.length; i++)
	  {
		  roadclosedcoordinates.push({});
	  }

	  // Create Objects Empty array (Traffic and Congestion Data Points) of object before populating
	  for (var i = 0; i < hazardclosedwazelatitude.length; i++)
	  {
		  hazardcoordinates.push({});
	  }

	  // Create Objects Empty array (Traffic and Congestion Data Points) of object before populating
	  for (var i = 0; i < accidentwazelatitude.length; i++)
	  {
		  accidentcoordinates.push({});
	  }

	  // Create Objects Empty array (Traffic and Congestion Data Points) of object before populating
	  for (var i = 0; i < policewazelatitude.length; i++)
	  {
		  policecoordinates.push({});
	  }

	  // Create Objects Empty array (Traffic and Congestion Data Points) of object before populating
	  for (var i = 0; i < jamwazelatitude.length; i++)
	  {
		  jamcoordinates.push({});
	  }
	  

	  // Create Objects Empty array (Traffic and Congestion Data Points) of object before populating
	  for (var i = 0; i < startlongitudearray.length; i++)
	  {
	  	startcoordinates.push({});
	  }

	  // Create Objects Empty array (Traffic and Congestion Data Points) of object before populating
	  for (var i = 0; i < endlongitudearray.length; i++)
	  {
	  	endcoordinates.push({});
	  }

	  for( var j = 0; j < startlongitudearray.length; j++)
	  {
	  	startcoordinates[j].lat = policewazelatitude[j];
	  	startcoordinates[j].lng = startlongitudearray[j];
	  }

	  for( var j = 0; j < endlongitudearray.length; j++)
	  {
	  	endcoordinates[j].lat = policewazelatitude[j];
	  	endcoordinates[j].lng = endlongitudearray[j];
	  }

	  //////

	  for( var j = 0; j < policewazelatitude.length; j++)
	  {
		  policecoordinates[j].lat = policewazelatitude[j];
		  policecoordinates[j].lng = policewazelongitude[j];
	  }

	  for( var j = 0; j < hazardwazelatitude.length; j++)
	  {
		  hazardcoordinates[j].lat = hazardwazelatitude[j];
		  hazardcoordinates[j].lng = hazardlongitude[j];
	  }

	  for( var j = 0; j < accidentwazelatitude.length; j++)
	  {
		  accidentcoordinates[j].lat = accidentwazelatitude[j];
		  accidentcoordinates[j].lng = accidentwazelongitude[j];
	  }

	  for( var j = 0; j < jamwazelatitude.length; j++)
	  {
		  jamcoordinates[j].lat = jamwazelatitude[j];
		  jamcoordinates[j].lng = jamwazelongitude[j];
	  }

	  for( var j = 0; j < roadclosedwazelatitude.length; j++)
	  {
		  roadclosedcoordinates[j].lat = roadclosedwazelatitude[j];
		  roadclosedcoordinates[j].lng = roadclosedwazelongitude[j];
	  }
	  

	  var incidentmarkers = [];

	  var policemarkers = [];
	  var accidentmarkers = [];
	  var jammarkers = [];
	  var roadmarkers = [];
	  var hazardmarkers = [];
	  
	  </script>
	  
	  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyApUGdIzXiSr5bUX45d-P01gKVGA-9jiKg&signed_in=true&callback=initialize"></script>
	  <script> 
	  
	  function initialize() 
	  {
		  //var myLatLng = {lat: 40.4867, lng: -74.444};
		  var map = new google.maps.Map(document.getElementById('map'), {zoom: 12,center: new google.maps.LatLng(myLatLng.lat, myLatLng.lng),mapTypeId: google.maps.MapTypeId.ROADMAP});
		  var geocoder = new google.maps.Geocoder();

		  document.getElementById('submit').addEventListener('click', function() {geocodeAddress(geocoder, map);});

		  document.getElementById('Accidents').addEventListener('click', function() {drawaccidentmarkers(map);});

		  //document.getElementById('Police').addEventListener('click', function() {drawpolicemarkers(map);});

		  //document.getElementById('Jam').addEventListener('click', function() {drawjammarkers(map);});

		  //document.getElementById('hazard').addEventListener('click', function() {drawhazardmarkers(map);});

		 // document.getElementById('road').addEventListener('click', function() {drawroadmarkers(map);});
		  
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

		function drawmarkers(map)
		  { 
			  //toggleEventMarkers();
			  //toggleConsMarkers();
			  for(var j = 0; j< startcoordinates.length ; j++)
			  {
					
				 markernew = new google.maps.Marker({
				    map: map,
					position: new google.maps.LatLng(startcoordinates[j].lat,startcoordinates[j].lng),
					title: accidenttype[j],
					icon: 'caraccident.png',
					animation: google.maps.Animation.DROP
	   			});

				 var content = accidenttype[j];   
               var infowindow = new google.maps.InfoWindow();

				google.maps.event.addListener(markernew,'click', (function(markernew,content,infowindow){ 
				        return function() 
				        {
				           infowindow.setContent(content);
				           infowindow.open(map,markernew);
				        };
				    })(markernew,content,infowindow)); 

	   			incidentmarkers.push(markernew);

			  }

			  markernew.setMap(map);
		  }

		function drawaccidentmarkers(map)
		  { 
			  //toggleEventMarkers();
			  //toggleConsMarkers();
			  for(var j = 0; j< accidentcoordinates.length ; j++)
			  {
				 consmarkernew = new google.maps.Marker({
				    map: map,
					position: new google.maps.LatLng(accidentcoordinates[j].lat,accidentcoordinates[j].lng),
					title: accidentwazethumbsup[j].concat(accidentwazesubtype),
					icon: 'caraccident.png',
					animation: google.maps.Animation.DROP
	   			});

				 var content = accidentwazethumbsup[j].concat(accidentwazesubtype);   
            	 var infowindow = new google.maps.InfoWindow();

				google.maps.event.addListener(consmarkernew,'click', (function(consmarkernew,content,infowindow){ 
				        return function() 
				        {
				           infowindow.setContent(content);
				           infowindow.open(map,consmarkernew);
				        };
				    })(consmarkernew,content,infowindow)); 

	   			accidentmarkers.push(consmarkernew);

			  }

			  consmarkernew.setMap(map);
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
      <input id="Construction" type="button" value="Plot Construction">
      <input id="Events" type="button" value="Plot Events">
      <input id="Clear" type="button" value="Clear Markers">
      <br>
    </div>
    

	
	
	<footer> 
	&copy 2015 Group 7
	</footer>
	
</div>

		    <div id="inputform" align="center">
		    <form action="Home.php" method = "post" role="form">
		    	<p> Select Day of the Week </p>
		 		 <select name="day">
				    <option value="monday">Monday</option>
				    <option value="tuesday">Tuesday</option>
				    <option value="wednesday">Wednesday</option>
				    <option value="thursday">Thursday</option>
				    <option value="friday">Friday</option>
				    <option value="weekend">Weekend</option>
		  		</select>
		  		<br><br><br>
		  		<p> Select Severity Level </p>
		  		 <select name="severity">
				    <option value="level0">0</option>
				    <option value="level1">1</option>
				    <option value="level2">2</option>
				    <option value="level3">3</option>
				    <option value="levelall">All</option>
		  		</select>
		 			 <br><br>
		 			 <input class="form-control" placeholder="zipcode" name="zipcode" type="text" style='width:170px'>
		 			<br><br>
		  		<input type="submit" name="submit">
			</form>
					<br>
	</div>

</body>

	
</html>



