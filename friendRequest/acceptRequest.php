<?php

session_start();
include_once __DIR__ . '/../connection.php';
	
$collection = $client->FacebookMongo->FriendWith;
$collectionReq = $client->FacebookMongo->FriendshipRequest;

if($_POST['action']=="Accept")
{
	$insertOneResult = $collection->insertOne([
		'StartDate' => date('m/d/Y h:i:s a'),
		/*'memberEmail' => $_SESSION["login_memberemail"],
		'memberEmailWithFriend' => $_POST["sender_email"],*/
		'memberEmail' => $_POST["sender_email"],
		'memberEmailWithFriend' => $_SESSION["login_memberemail"],
		
	]);		
	
	if($insertOneResult->getInsertedCount()	== 1)
	{
		$delResult = $collectionReq->deleteMany([
			'memberEmailReceiver' => $_SESSION["login_memberemail"],
			'memberEmailSender' => $_POST["sender_email"],
		]);
	}			
		
}

?>