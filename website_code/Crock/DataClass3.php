
<?php 


// Below these are used for Waze Alerts
$wazelatitude0= array();
$wazelongitude0 = array();
$thumbsup0 = array();
$wazesubtype0 = array();

class TrafficData3
{
	// Construt() is constructor and defines our data
	function __construct()
	{
		
	}
	
	function getwazealerts($wazezipcode, $searchstring)
	{
		global $wazelatitude0;
		global $wazelongitude0;
		global $thumbsup0;
		global $wazesubtype0;
		
		$wazelatitude0= array();
		$wazelongitude0 = array();
		$thumbsup0 = array();
		$wazesubtype0 = array();
		
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
				$wazelatitude0[] = $row["latitude"];
				$wazelongitude0[] = $row["longitude"];
				$thumbsup0[] = $row["numofThumbs"];
				$wazesubtype0[] = $row["incidentsubtype"];
			}
		} else
		{
			echo "0 results";
		}
		
	}
	
	function getwazearray($text)
	{
	
		global $wazelatitude0;
		global $wazelongitude0;
		global $thumbsup0;
		global $wazetype;
		global $wazesubtype0;
	
	
		if($text == "LAT")
		{
			return $wazelatitude0;
		}
		elseif($text == "LONG")
		{
			return $wazelongitude0;
		}
		elseif($text == "THUMBS")
		{
			return $thumbsup0;
		}
		elseif($text == "TYPE")
		{
			return $wazetype;
		}
		else{
			return $wazesubtype0;
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