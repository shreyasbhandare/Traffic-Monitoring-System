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
 <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Login</title>

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
<body >

<div class="container">
        <div class="row">
			<div class="col-md-6 col-md-offset-0">
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title"><b> <font size ="5"> Sign in to proceed </font></b></h3>
                            
                             <p style = "color:red"> 
                                        
                                        <?php 
											if($SignUpResult == "SUCCESS")
											{
												echo "Registeration Successful please Login to Proceed.";
											}
										?>
							</p>
                            
                            
                        </div>
                        <div class="panel-body">
                            <p class="lead"><strong>Traffic Predictor</strong></p>
                            <p class="text-justify"> Group 7</p>
                            <p class="text-justify"> Kush Patel .<br> Tejas Ravi .<br> Aditya .<br> Shreyas .<br> Vinayak.<br> Jefferry</p>
                            
                        </div>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
            <div class="col-md-6 col-md-offset-0">
                <div class="login-panel panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><font size ="5">
                                           
                        Please Sign In</font></h3>
                    </div>
                    <div class="panel-body">
                        <form name = "logintable" action = "loginchk.php" method = "post" role="form" script="margin-left:auto">
                            <fieldset>
                                <div class="form-group">
                                    Username: <input class="form-control" placeholder="Username" name="username" type="text" autofocus>
                                </div>
                                <div class="form-group">
                                    Password: <input class="form-control" placeholder="Password" name="password" type="password" value="">
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input name="remember" type="checkbox" value="Remember Me">Remember Me </input>
                                        <br>
                                        <p style = "color:red"> 
                                        <?php 
                                        if (isset($_SESSION["loginresult"])) {
										$LoginResult = $_SESSION["loginresult"];
										
										if($LoginResult == "FAIL")
										{
											echo "Invalid Username or Password. Please Try again.";
										}
										}										
										?>
										</p>
                                        
                                    </label>
                                </div>
                                <input type="submit" name="Submit" value="Submit" class="btn btn-primary btn-lg" ></input>        
                            </fieldset>
                        </form>
                        <form method="get" action="Register.php" script="margin-right:auto">
    							<button type="submit" class="btn btn-primary btn-lg" >Sign Up Here</button>
						</form>   
                        <br>
                       
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
    
    <p>
    
    
    </p>

</body>
</html>