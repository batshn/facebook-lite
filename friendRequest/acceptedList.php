<?php
	
session_start();
include_once __DIR__ . '/../connection.php';
$collection = $client->FacebookMongo->Members;
$colFriends = $client->FacebookMongo->FriendWith;
$output='';

/******** Select posts **************/
if(isset($_POST['action']))
	{
		$memberEmails = $colFriends->find([ 
			'memberEmail'=> $_SESSION["login_memberemail"]
			]);

		$memberEmailsFr = $colFriends->find([ 
				'memberEmailWithFriend'=> $_SESSION["login_memberemail"]
				]);

		$memberIDS=array();	
		foreach($memberEmails as $row) $memberIDS[]=$row['memberEmailWithFriend'];
		foreach($memberEmailsFr as $row) $memberIDS[]=$row['memberEmail'];

		$cursor = $collection->find([
			 'MemberEmail' => array('$in'=>  $memberIDS) 
		  ]);
		
	
	//	$cursor = $collection->aggregate($pipeline, $options);
	
		foreach($cursor as $row)
		{
				$output .= '<div class="frList">'. $row['FirstName'] .' '. $row['LastName'] .' ';
				$output .= '<button type="button" name="fr_reject" id=' .$row['MemberEmail'].' class="fr_reject btn btn-sm btn-link">Unfriend</button>';
				$output .='</div>';
				$output .='<hr style="margin-top: 2px; margin-bottom: 2px">';
		}

		echo $output;

	}


?>