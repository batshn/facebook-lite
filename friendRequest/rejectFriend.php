<?php

session_start();
include_once __DIR__ . '/../connection.php';
	
$collection = $client->FacebookMongo->FriendWith;

if($_POST['action']=="Reject")
{
	$delResult = $collection->deleteMany([
		'memberEmail' => $_SESSION["login_memberemail"],
		'memberEmailWithFriend' => $_POST["sender_email"],
	]);		
	
	$delResult = $collection->deleteMany([
		'memberEmail' => $_POST["sender_email"],
		'memberEmailWithFriend' => $_SESSION["login_memberemail"],
	]);	
		
}

?>