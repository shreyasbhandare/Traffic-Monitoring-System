
<?php 


// Below these are used for Waze Alerts
$wazelatitude= array();
$wazelongitude = array();
$thumbsup = array();
$wazesubtype = array();

class TrafficData2
{
	// Construt() is constructor and defines our data
	function __construct()
	{
		
	}
	
	function getwazealerts($wazezipcode, $searchstring)
	{
		global $wazelatitude;
		global $wazelongitude;
		global $thumbsup;
		global $wazesubtype;
		
		$wazelatitude= array();
		$wazelongitude = array();
		$thumbsup = array();
		$wazesubtype = array();
		
		$host="localhost"; // Host name
		$username="kush"; // Mysql username
		$password="password"; // Mysql password
		$db_name="trafficprediction"; // Database name
		$tbl_name="livewazealerts"; // Table name
		
		// Create connection
		$conn = new mysqli($host, $username, $password, $db_name);
		// Check connection
		if ($conn->connect_error)
		{
			die("Connection failed: " . $conn->connect_error);
		}
		
		$wazesql = "SELECT `numofThumbs`, `incidentsubtype`, `latitude`, `longitude` FROM `livewazealerts` WHERE `ZipCode`=\"" .$wazezipcode. "\" and `incidenttype`=\"" .$searchstring. "\"";
		$result = $conn->query($wazesql);
		
		echo $wazesql;
		
		if ($result->num_rows > 0)
		{
			// output data of each row
			while($row = $result->fetch_assoc())
			{
				$wazelatitude[] = $row["latitude"];
				$wazelongitude[] = $row["longitude"];
				$thumbsup[] = $row["numofThumbs"];
				$wazesubtype[] = $row["incidentsubtype"];
			}
		} else
		{
			echo "0 results";
		}
		
	}
	
	function getwazearray($text)
	{
	
		global $wazelatitude;
		global $wazelongitude;
		global $thumbsup;
		global $wazetype;
		global $wazesubtype;
	
	
		if($text == "LAT")
		{
			return $wazelatitude;
		}
		elseif($text == "LONG")
		{
			return $wazelongitude;
		}
		elseif($text == "THUMBS")
		{
			return $thumbsup;
		}
		elseif($text == "TYPE")
		{
			return $wazetype;
		}
		elseif ($text == "SEV")
		{
			return $severity;
		}
		else{
			return $wazesubtype;
		}
	}
	
	
	
	function getzipcode()
	{
		$zipcode = "NULL";
		if (isset($_SESSION["ZipCode"])) {
			$zipcode = $_SESSION["ZipCode"];
		}
		return $zipcode;
	}
	
}




?>