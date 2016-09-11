<?php
session_start();
$host="localhost"; // Host name 
$username="kush"; // Mysql username 
$password="password"; // Mysql password 
$db_name="customerdb"; // Database name 
$tbl_name="logincredentials"; // Table name 


// Create connection
$conn = new mysqli($host, $username, $password, $db_name);
// Check connection
if ($conn->connect_error)
{
	die("Connection failed: " . $conn->connect_error);
}

// username and password sent from form 
$myusername=$_POST['username']; 
$mypassword=$_POST['password']; 

$sql = "SELECT `FirstName`,`ZipCode` FROM `" . $tbl_name . "` WHERE `username`=\"" .$myusername . "\" and `password`=\"" .$mypassword."\"";
$result = $conn->query($sql);

// Mysql_num_row is counting table row
$count = $result->num_rows;

// If result matched $myusername and $mypassword, table row must be 1 row
if($count==1){
	
$row = $result->fetch_assoc();
$firstname = $row['FirstName'];
$zipcode = $row['ZipCode'];
// Register $myusername, $mypassword and redirect to file "LiveTraffic.php"
$_SESSION["myusername"]=$myusername;
$_SESSION["ZipCode"]=$zipcode;
$_SESSION["firstname"]= $firstname;
$_SESSION["loginresult"]="SUCCESS";
echo $firstname;
$conn->close();
header("location:Home3.php");

}
else 
{
$_SESSION["loginresult"]="FAIL";
$conn->close();
header("location:Login.php");
}
?>