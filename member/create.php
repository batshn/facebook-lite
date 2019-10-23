<?php 

try {

	include_once __DIR__ . '/../connection.php';
	session_start();

	$collection = $client->FacebookMongo->Members;

	$memberFound = $collection->count(['MemberEmail' => $_POST["txtEmail"]]);

	if($memberFound==0)
	{   
		$insertOneResult = $collection->insertOne([
			'MemberEmail' => $_POST["txtEmail"],
			'LoginPassword' => $_POST["txtPassword"],
			'FirstName' => $_POST["txtFirstName"],
			'LastName' => $_POST["txtLastName"],
			'ScreenName' => $_POST["txtScreenName"],
			'BirthDate' => $_POST["txtBirthDate"],
			'Gender' => $_POST["cbGender"],
			'Status' => $_POST["cbStatus"],
			'mLocation' => $_POST["txtLocation"],
			'Visibility' => $_POST["cbVisibility"],
		]);

		if($insertOneResult->getInsertedCount()	== 1)
		{
			$_SESSION['login_screenname'] = $_POST["txtScreenName"];
			$_SESSION['login_memberemail'] = $_POST["txtEmail"];
			$_SESSION['loggedin'] = true;

			header("location: /facebook-mongo/main.php");
		}
		else header("location: index.php");
		
	}
	else header("location: index.php");

} catch(MongoCursorException $e) {
	
	header("location: index.php");
}
	
?>