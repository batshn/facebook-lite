<?php

	session_start();

	if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == true){
	    header("location: main.php");
	    exit;
	}

	require_once "connection.php";
	$collection = $client->FacebookMongo->Members;

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$member = $collection->findOne([
				'MemberEmail' => $_POST['m_username'],
				'LoginPassword' => $_POST['m_password']
			  ]);

		if($_POST['m_username'] == $member['MemberEmail'] && $_POST['m_password'] == $member['LoginPassword']){ 
			
	    	$_SESSION['login_screenname'] =$member['ScreenName'];
	    	$_SESSION['login_memberemail'] = $member['MemberEmail'];
	    	$_SESSION['loggedin'] = true;
			
			header("location: main.php"); 
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Facebook Lite </title>
	<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="asset/main.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script> 
</head>
<body>
<div class="login-form">
	<h2 class="text-center">Facebook-Lite</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">  
		<div class="avatar">
			<img src="asset/img/avatar.png" alt="Avatar">
		</div>           
        <div class="form-group">
        	<input type="text" class="form-control input-lg" name="m_username" placeholder="Email address" required="required">	
        </div>
		<div class="form-group">
            <input type="password" class="form-control input-lg" name="m_password" placeholder="Password" required="required">
        </div>        
        <div class="form-group clearfix">
            <button type="submit" class="btn btn-primary btn-lg pull-right">Sign in</button>
        </div>		
    </form>
	<div class="hint-text">Don't have an account? <a href="member/index.php">Create a new account</a></div>
</div>
</body>
</html>                            