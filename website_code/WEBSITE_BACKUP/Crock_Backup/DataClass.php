
<?php 

session_start();

// Below these are used for congestion points
$startlatitude= array();
$startlongitude = array();
$endlatitude = array();
$endlongitude = array();
$delay = array();
$accidenttype = array();
$severity = array();


class TrafficData
{
	// Construt() is constructor and defines our data
	function __construct()
	{
		
	}
	
	function getdatabyseverity($dayofweek, $zipcode)
	{
		global $startlatitude;
		global $startlongitude;
		global $endlatitude;
		global $endlongitude;
		global $delay;
		
		
		$host="localhost"; // Host name
		$username="kush"; // Mysql username
		$password="password"; // Mysql password
		$db_name="trafficprediction"; // Database name
		$tbl_name="livecongestion"; // Table name
		
		// Create connection
		$conn = new mysqli($host, $username, $password, $db_name);
		// Check connection
		if ($conn->connect_error)
		{
			die("Connection failed: " . $conn->connect_error);
		}
		
		
		//SELECT `Street`, `StartLatitude`, `StartLongitude`, `EndLatitude`, `EndLongitude`, `Delay` FROM `livecongestion` WHERE `ZipCode` = "11222" AND `Day`="Weekend"
		$sql = "SELECT `Street`, `CongestionType` ,`StartLatitude`, `StartLongitude`, `EndLatitude`, `EndLongitude`, `Delay` FROM `" . $tbl_name . "` WHERE `ZipCode`=\"" .$zipcode . "\" and `Day`=\"" .$dayofweek."\"";
		$result = $conn->query($sql);
		
		echo $sql;
		
		if ($result->num_rows > 0)
		{
			// output data of each row
			while($row = $result->fetch_assoc())
			{
				$startlatitude[] = $row["StartLatitude"];
				$startlongitude[] = $row["StartLongitude"];
				$endlatitude[] = $row["EndLatitude"];
				$endlongitude[] = $row["EndLongitude"];
				$delay[] = $row["Delay"];
				//$merge = $row["Type"]." - ".$row["Street"];
				$accidenttype[] = $row["CongestionType"];
			}
		} else
		{
			echo "0 results";
		}
	}
	
	function getseverity($dayofweek, $zipcode, $sev)
	{
		global $startlatitude;
		global $startlongitude;
		global $endlatitude;
		global $endlongitude;
		global $accidenttype;
		
	
	
		$host="localhost"; // Host name
		$username="kush"; // Mysql username
		$password="password"; // Mysql password
		$db_name="trafficprediction"; // Database name
		$tbl_name="livecongestion"; // Table name
	
		// Create connection
		$conn = new mysqli($host, $username, $password, $db_name);
		// Check connection
		if ($conn->connect_error)
		{
			die("Connection failed: " . $conn->connect_error);
		}
	
	
		//SELECT `Street`, `StartLatitude`, `StartLongitude`, `EndLatitude`, `EndLongitude`, `Delay` FROM `livecongestion` WHERE `ZipCode` = "11222" AND `Day`="Weekend"
		$sql = "SELECT `Street`, `CongestionType` ,`Severity`, `StartLatitude`, `StartLongitude`, `EndLatitude`, `EndLongitude` FROM `" . $tbl_name . "` WHERE `ZipCode`=\"" .$zipcode . "\" and `Severity`=" .$sev. " and `Day`=\"" .$dayofweek."\"";
		$result = $conn->query($sql);
	
		echo $sql;
	
		if ($result->num_rows > 0)
		{
			// output data of each row
			while($row = $result->fetch_assoc())
			{
				$startlatitude[] = $row["StartLatitude"];
				$startlongitude[] = $row["StartLongitude"];
				$endlatitude[] = $row["EndLatitude"];
				$endlongitude[] = $row["EndLongitude"];
				$accidenttype[] = $row["CongestionType"];
				$severity[] = $row["Severity"];
			}
		} else
		{
			echo "0 results";
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
	
	function getarray($text)
	{
		
		global $startlatitude;
		global $startlongitude;
		global $endlatitude;
		global $endlongitude;
		global $delay;
		global $accidenttype;
		
		
		if($text == "START_LAT")
		{	
			//print_r($startlatitude);
			return $startlatitude;
		}
		elseif($text == "START_LONG")
		{
			return $startlongitude;
		}
		elseif($text == "END_LAT")
		{
			return $endlatitude;
		}
		elseif($text == "END_LONG")
		{
			return $endlongitude;
		}
		elseif($text == "DELAY")
		{
			return $delay;
		}
		else{
			return $accidenttype;
		}
	}
}




?>