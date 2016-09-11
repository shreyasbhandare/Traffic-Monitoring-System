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
$signupusername=$_POST['username']; 
$signuppassword1 = $_POST['password']; 
$signuppassword2=$_POST['password2']; 
$signupfirstname=$_POST['fname'];
$signupemail=$_POST['email'];
$signpzipcode =$_POST['zipcode'];

if($signuppassword1 != $signuppassword2)
{
	$_SESSION["signupresult"]="PASSWORD_MATCH";
	echo $_SESSION["signupresult"];
	header("location:Register.php");
}
elseif ((strlen($signpzipcode) < 5) || (strlen($signpzipcode) > 5))
{
	$_SESSION["signupresult"]="ZIPCODE_LENGTH";
	echo $_SESSION["signupresult"];
	header("location:Register.php");
}
elseif ((strpos($signupemail, '@') === false))
{
	$_SESSION["signupresult"]="EMAIL_FORMAT";
	echo $_SESSION["signupresult"];
	header("location:Register.php");
}
elseif ((strlen($signupusername) > 1))
{
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
	
	$sql = "SELECT `FirstName` FROM `" . $tbl_name . "` WHERE `username`=\"" .$signupusername."\"";
	$result = $conn->query($sql);
	
	// Mysql_num_row is counting table row
	$count = $result->num_rows;
	
	if($count==1)
	{
		$_SESSION["signupresult"]="USERNAME_EXISTS";
		$conn->close();
		echo $_SESSION["signupresult"];
		header("location:Register.php");
	}
	else
	{
		$sql = "INSERT INTO `" . $tbl_name . "`(`username`, `password`, `FirstName`, `Email`, `ZipCode`) VALUES (\"".$signupusername."\",\"".$signuppassword1."\",\"".$signupfirstname."\",\"".$signupemail."\",\"".$signpzipcode."\")";
		
		if ($conn->query($sql) === TRUE) 
		{
			echo "New record created successfully";
			$_SESSION["signupresult"]="SUCCESS";
			header("location:Login.php");
		} else 
		{
			echo "Error: " . $sql . "<br>" . $conn->error;
		}
		
		$conn->close();
	}
	
}



?>