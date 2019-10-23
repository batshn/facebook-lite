<?php 

try {

	include_once __DIR__ . '/../connection.php';
	session_start();
	

	$collection = $client->FacebookMongo->Members;
	$colPost = $client->FacebookMongo->Posts;

	$postFound = $colPost->count(['memberEmail' => $_SESSION['login_memberemail']]);

	if($postFound==0)
	{ 
		$deleteResult = $collection->deleteMany(['MemberEmail' => $_SESSION['login_memberemail']]);

		if($deleteResult->getDeletedCount() > 0)
		{
			header("location: /facebook-mongo/logout.php");
		}
		else 
		{
			$_SESSION['error_msg']="It is impossible to delete the account and your account has not been activated.";
			header("location: /facebook-mongo/member_delete.php");
		}
	}
	else 
	{
		$updateOneResult=$collection->updateOne(
			['MemberEmail' => $_SESSION['login_memberemail']],
			[
				'$set' => [
					'Status' => 'InAcitve',
					]
			]
		);


		$_SESSION['error_msg']="It is impossible to delete the account and your account has not been activated.";
		header("location: /facebook-mongo/member_delete.php");
	}

}  catch(MongoCursorException $e) {
	
	$_SESSION['error_msg']="It is impossible to delete the account and your account has not been activated.";
	header("location: /facebook-mongo/member_delete.php");
}


?>