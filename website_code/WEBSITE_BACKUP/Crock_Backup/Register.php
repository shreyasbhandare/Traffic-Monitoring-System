<?php
session_start();
$SignUpResult = "NULL";

if (isset($_SESSION["signupresult"])) {
	$SignUpResult = $_SESSION["signupresult"];

}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">

  <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Register</title>

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

<title>Register</title>
</head>

<body>

	 <div class="container">
        <div class="row">
		<div class="panel panel-default col-md-offset-1">
			<div class="panel-heading"><font size = "15" >Enter Details</font></div>
			<div class="panel-body">
			<div class="col-lg-6">
   					 <form role="form" method = "post" action="createuser.php">
									<fieldset>
                                        <div class="form-group">
                                            <label>User Name:<p class="help-block" style="display:inline">You will use this to login</p> 
                                            
                                        <p style = "color:red"> 
                                        
                                        <?php 
											if($SignUpResult == "USERNAME_EXISTS")
											{
												echo "Username already exists. Please select a Unique One!";
											}
										?>
										</p>
                                                                                                                   
                                            </label>
                                            <input class="form-control" name = "username">
                                        </div>
                                        <div class="form-group">
                                            <label>Password: <p class="help-block" style="display:inline">Minimum 6 Characters</p>
								       <p style="color:red"> 
                                        
                                        <?php 
											if($SignUpResult == "PASSWORD_MATCH")
											{
												echo "Passwords do not match. Please ensure both passwords match";
											}
										?>
										</p>                                             
											 </label>
                                            <input class="form-control" name = "password" type = "password">
                                            
											
                                        </div>
										<div class="form-group">
                                            <label>Confirm Password</label>
                                            <input class="form-control" type = "password" name = "password2">
                                        </div>
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <input class="form-control" name="fname">
                                        </div>
                                        <div class="form-group">
                                            <label>Email ID</font> 
                                            </label>
                                       <p style = "color:red"> 
                                        
                                        <?php 
											if($SignUpResult == "EMAIL_FORMAT")
											{
												echo "Email not in correct format. Please try again";
											}
										?>
										</p>  
                                            <input class="form-control" placeholder = "Use format xyz@abc.com" type = "email" name = "email">
                                        </div>
                                        <div class="form-group">
                                            <label>Home Zip Code</label>
                                            <p style="color:red"> 
                                            <?php 
											if($SignUpResult == "ZIPCODE_LENGTH")
											{
												echo "Zip Code must by at least 5 digits. Please enter correct zip code";
											}
										?>
										</p>
                                            <input class="form-control" name = "zipcode">
                                        </div>
										<input type="submit" name="Submit" value="Submit" class="btn btn-primary btn-lg" ></input>
										&nbsp
										<input type = "reset" value = "Clear" class="btn btn-default btn-lg"></input>
									</fieldset>
                                    </form>
                                </div>
			</div>
		</div>
		</div>
    </div>

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