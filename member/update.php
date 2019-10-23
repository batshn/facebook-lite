<?php 

try {

	include_once __DIR__ . '/../connection.php';
	session_start();

	//echo $_POST["txtEmail"];

	$collection = $client->FacebookMongo->Members;

	$updateOneResult=$collection->updateOne(
		['MemberEmail' => $_POST["txtEmail"]],
		[
			'$set' => [
				'LoginPassword' => $_POST["txtPassword"],
				'FirstName' => $_POST["txtFirstName"],
				'LastName' => $_POST["txtLastName"],
				'ScreenName' => $_POST["txtScreenName"],
				'BirthDate' => $_POST["txtBirthDate"],
				'Gender' => $_POST["cbGender"],
				'Status' => $_POST["cbStatus"],
				'mLocation' => $_POST["txtLocation"],
				'Visibility' => $_POST["cbVisibility"]
				]
		]
	);


	$_SESSION['login_screenname'] = $_POST["txtScreenName"];
	$_SESSION['login_memberemail'] = $_POST["txtEmail"];

	if($updateOneResult->getMatchedCount() == 1)
	{
		header("location: /facebook-mongo/main.php");
	}
	else 
	{
		header("location: /facebook-mongo/member_update.php");
	}

} catch(MongoCursorException $e) {
	
	header("location: /facebook-mongo/member_update.php");
}



?>