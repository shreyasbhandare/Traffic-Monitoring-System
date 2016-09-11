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

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
 <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

	
	
	
    <title>Home</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- MetisMenu CSS -->
    <link href="css/metisMenu.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/sb-admin-2.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
	
 

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>
	 <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">Traffic Predictor</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">        
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li class="divider"></li>
                        <li><a href="Login.jsp"><i class="fa fa-sign-out fa-fw"></i> Logout</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

<div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        
                        <li>
                            <a href="Home3.php"><i class="fa fa-dashboard fa-fw"></i>Home</a>
                        </li>
                        <li>
                            <a href=""><i class="fa fa-dashboard fa-fw"></i> Alerts</a>
                        </li>
                        <li>
                            <a href="NNJSeverityy.php"><i class="fa fa-dashboard fa-fw"></i>NNJ Severity</a>
                        </li>
                        <li>
                            <a href="Chatstemp.php"><i class="fa fa-dashboard fa-fw"></i>Severity Charts</a>
                        </li>
                        <li>
                            <a href="Charts2.php"><i class="fa fa-dashboard fa-fw"></i>Accident Charts</a>
                        </li>
                        <li>
                            <a href="Parking.php"><i class="fa fa-dashboard fa-fw"></i>Parking</a>
                        </li>
                        <li>
                            <a href="FAQ.jsp"><i class="fa fa-dashboard fa-fw"></i>Restaurants</a>
                        </li>
						<li>
                            <a href="feedback.html"><i class="fa fa-dashboard fa-fw"></i>Feedback</a>
                        </li>
						<li>
                            <a href="aboutus.html"><i class="fa fa-dashboard fa-fw"></i>About Us</a>

                        </li>
                        
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
            
            
        </nav>

        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">                          <?php 
                                        if (isset($_SESSION["firstname"])) {
										$NAME = $_SESSION["firstname"];
											echo "Welcome ";
											echo $NAME;
											echo "<br> Your Home ZipCode:";
											echo $_SESSION["ZipCode"];
				
										}										
										?>
                    
             
                    
                    </h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>
        <div id="map" style="position:absolute">
        
        
        
        </div>
        
         <div class="col-lg-3" style="position:absolute; right:100px; top: 250px">
					<div class="panel panel-default">
						<div class="panel-heading">
							Query For Any Zip Code
						</div>
						<div class="panel-body">
						 <label>Enter Zip Code</label>
						 <input class="form-control" id="address" type="textbox" value="New Brunswick, NJ"><br>
					    <button id="submit" type="submit" class="btn btn-primary" value="Geocode" >Geocode</button>
					    <br><br>
					    <button type="submit" class="btn btn-primary" id="Accidents">Plot Accidents</button> 
								<button type="submit" class="btn btn-primary" id="Construction">Plot Closed Roads</button>
								<button type="submit" class="btn btn-primary"id="Events" >Plot Police</button>	
								<br>	
								<button type="submit" class="btn btn-primary" id="Clear" >Clear Markers</button>	
								
						</div>
					</div>
                </div>
        				
	  <style>
      #map {
        width: 1000px;
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
        
        
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->
	 
  
 
	
	
    <!-- jQuery -->
    <script src="js/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

 	<!-- Metis Menu Plugin JavaScript -->
    <script src="js/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="js/sb-admin-2.js"></script>
</body>
</html>