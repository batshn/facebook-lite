<?php

session_start();
include_once __DIR__ . '/../connection.php';
	
$collection = $client->FacebookMongo->FriendshipRequest;
$collFr = $client->FacebookMongo->FriendWith;

if($_POST['action']=="Request")
{
	$mf1 = $collection->count(['memberEmailSender' => $_SESSION["login_memberemail"],'memberEmailReceiver' => $_POST["receiver_email"] ]); 
	$mf2 = $collection->count(['memberEmailReceiver' => $_SESSION["login_memberemail"],'memberEmailSender' => $_POST["receiver_email"] ]);

	$mf3 = $collFr->count(['memberEmail' => $_SESSION["login_memberemail"], 'memberEmailWithFriend' => $_POST["receiver_email"] ]);
	$mf4 = $collFr->count(['memberEmailWithFriend' => $_SESSION["login_memberemail"], 'memberEmail' => $_POST["receiver_email"] ]);
	
	$memberFound = $mf1+$mf2+$mf3+$mf4;

	if($memberFound==0)
	{ 
		$insertOneResult = $collection->insertOne([
			'RequestDate' => date('m/d/Y h:i:s a'),
			'Status' => 'Request',
			'memberEmailSender' => $_SESSION["login_memberemail"],
			'memberEmailReceiver' => $_POST["receiver_email"],
		]);		
		
		if($insertOneResult->getInsertedCount()	== 1)
		{
			echo "Friend request has been sent";
		}
	}
	else 
	{
		echo "You have already sent friend request or became with this member! ";
	}
			
}

?>