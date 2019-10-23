<?php
	
session_start();
include_once __DIR__ . '/../connection.php';
$collection = $client->FacebookMongo->Members;
$output='';

/******** Select posts **************/
if(isset($_POST['action']))
{
	$options = [
		'allowDiskUse' => TRUE
	];

	$pipeline = [
		[
			'$project' => [
				'_id' => 0,
				'm' => '$$ROOT'
			]
		], [
			'$lookup' => [
				'localField' => 'm.MemberEmail',
				'from' => 'FriendshipRequest',
				'foreignField' => 'memberEmailSender',
				'as' => 'f'
			]
		], [
			'$unwind' => [
				'path' => '$f',
				'preserveNullAndEmptyArrays' => FALSE
			]
		],[
			'$match' => [
				'f.status' => [
					'$ne' => 'Accept'
				],
				'f.memberEmailReceiver' => $_SESSION["login_memberemail"]
			]
		], [
			'$project' => [
				'm.FirstName' => '$m.FirstName',
				'm.LastName' => '$m.LastName',
				'f.memberEmailSender' => '$f.memberEmailSender',
				'_id' => 0
			]
		]
	];

	$cursor = $collection->aggregate($pipeline, $options);

	
	foreach($cursor as $row)
	{
		$output .= '<div class="frList">'. $row['m']->FirstName .' '. $row['m']->LastName .' ';
		$output .= '<button type="button" name="fr_accept" id=' .$row['f']->memberEmailSender .' class="fr_accept btn btn-sm btn-link">Accept</button>';
		$output .='</div>';
		$output .='<hr style="margin-top: 2px; margin-bottom: 2px">';
	}
	
	echo $output;

}


?>