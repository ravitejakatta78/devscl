<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>INSPINIA | Login 2</title>

    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="assets/css/animate.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script src="assets/js/jquery-3.1.1.min.js"></script>
<style>
@media all and (max-width: 480px) {
  #welcomemsg {
    display: none;
  }
}
    </style>
<script>


$(function() {
    $.post('login.php', { width: screen.width, height:screen.height }, function(json) {
        alert("sda");
        if(json.outcome == 'success') {
            // do something with the knowledge possibly?
        } else {
            alert('Unable to let PHP know what the screen resolution is!');
        }
    },'json');
});

    </script>


</head>


<?php
session_start();
error_reporting(E_ALL); 
include('common.php');
if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['width']) ) {	
    if(isset($_POST['width']) && isset($_POST['height'])) {
        $_SESSION['screen_width'] = $_POST['width'];

    }
}
if(@$_SESSION['screen_width'] <= 500 ) {
    $device = 'mobile';
}
else{
    $device = 'lg_device';
}
if($device == 'mobile'){
    $url = 'school/dashboard-mobile.php';
}else{
   $url = 'admin/schools.php';      
}
$userid = current_userid(); 
    if(!empty($userid)){
        if($userid == 1){ //admin
            $url = 'admin/schools.php';
        }
        else{ //schools
            $url = $url; 
        }
        header("Location: $url");
	}
	$message = '';
	if($_SERVER["REQUEST_METHOD"] == "POST") {	
        
	if(isset($_POST['adminlogin'])){	
	if(!empty($_POST['username'])){	
	if(!empty($_POST['password'])){		
	$mymailid = mysqli_real_escape_string($conn,$_POST['username']);	
	$mypassword = mysqli_real_escape_string($conn,$_POST['password']); 	
	$row = runQuery("SELECT * FROM users WHERE user_name = '".$mymailid."' or user_email = '".$mymailid."'");	
 	if(!empty($row['id'])){	
	if(($row['user_password']) == md5($mypassword) ){	
	    $_SESSION['sessionusersid'] = $row['id']; 
        
        header("Location: $url");
	} else {			
	$message .= "Your Login password is invalid";	
	}		
	}  else {	
	$message .= "Your Login email invalid";	
	}		
	}else{		
	$message .= "Enter your password";		
	}	
	}else{			
	$message .= "Enter your email";		
	}
	}	
	}
	?>

<body class="gray-bg">

    <div class="loginColumns animated fadeInDown">
        <div class="row">

            <div class="col-md-6" id="welcomemsg">
                <h2 class="font-bold">Welcome to IN+</h2>

                <p>
                    Perfectly designed and precisely prepared admin theme with over 50 pages with extra new web app views.
                </p>

                <p>
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s.
                </p>

                <p>
                    When an unknown printer took a galley of type and scrambled it to make a type specimen book.
                </p>

                <p>
                    <small>It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged.</small>
                </p>

            </div>
            <div class="col-md-6">
                <div class="ibox-content">
                    <form class="m-t" role="form" action="" method="POST">
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="Username" id="username" name="username" required="">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" placeholder="Password" id="password" name="password" required="">
                        </div>
                        <button type="submit" class="btn btn-primary block full-width m-b" name="adminlogin">Login</button>

                        <a href="#">
                            <small>Forgot password?</small>
                        </a>

                        <p class="text-muted text-center">
                            <small>Do not have an account?</small>
                        </p>
                        <a class="btn btn-sm btn-white btn-block" href="register.html">Create an account</a>
                    </form>
                    <p class="m-t">
                        <small>Inspinia we app framework base on Bootstrap 3 &copy; 2014</small>
                    </p>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-md-6">
                Copyright Example Company
            </div>
            <div class="col-md-6 text-right">
               <small>© 2014-2015</small>
            </div>
        </div>
    </div>

</body>

</html>
