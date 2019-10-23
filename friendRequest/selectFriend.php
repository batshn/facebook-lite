<?php
	
session_start();
include_once __DIR__ . '/../connection.php';
$collection = $client->FacebookMongo->Members;
$output='';

/******** Select member **************/
if(isset($_POST['query']))
{
	$searchValue=$_POST["query"];
	$query = [
		'$or' => [
			[
				'FirstName' => new MongoDB\BSON\Regex('^.*'.$searchValue.'.*$', 'i')
			], [
				'LastName' => new MongoDB\BSON\Regex('^.*'.$searchValue.'.*$', 'i')
			], [
				'MemberEmail' => new MongoDB\BSON\Regex('^.*'.$searchValue.'.*$', 'i')
			]
		],
		'MemberEmail' => [
			'$ne' => $_SESSION["login_memberemail"]
		]
	];

	$options = [];

	$cursor = $collection->find($query, $options);

	foreach($cursor as $row) 
	{
		$output .= '<div class="frList">'. $row['FirstName'] .' '. $row['LastName'] .' ';
		$output .= '<button type="button" name="fr_request" id=' .$row['MemberEmail'] .' class="fr_request btn btn-sm btn-link">Request</button>';
		$output .='</div>';
		$output .='<hr style="margin-top: 2px; margin-bottom: 2px">';
	}
	echo $output;

}


?>