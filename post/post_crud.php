<?php
	
session_start();
include_once __DIR__ . '/../connection.php';
$output='';


try
{
	if(isset($_POST['action']))
	{
		
		$collectionPost = $client->FacebookMongo->Posts;
		$collectionPL = $client->FacebookMongo->PostLikes;
		$collectionComment = $client->FacebookMongo->Comments;
		$collectionCL = $client->FacebookMongo->CommentLikes;

		switch($_POST['action'])
		{
			/******** add a post **************/
			case "Post": 
			{
				$insertOneResult = $collectionPost->insertOne([
					'postBody' => $_POST['postBody'],
					'postDate' => date('m/d/Y h:i:s a', time()),
					'memberEmail' => $_SESSION["login_memberemail"],
				]);

			}; break;

			/******** like a post **************/	
			case "like":
			{				
				$insertOneResult = $collectionPL->insertOne([
					'memberEmail' => $_SESSION["login_memberemail"],
					'postID' => $_POST['id'],
				]);	

			}; break;

			/******** unlike a post **************/	
			case "unlike":
			{				
				$deleteResult = $collectionPL->deleteMany([
					'memberEmail' => $_SESSION['login_memberemail'],
					'postID' => $_POST['id'],
				]);
			}; break;

			/******** post a comment **************/	
			case "CommentAdd":
			{
				$insertOneResult = $collectionComment->insertOne([
					'commentBody' => $_POST['commentBody'],
					'postID' => $_POST['postid'],
					'commentDate' => date('m/d/Y h:i:s a', time()),
					'memberEmail' => $_SESSION["login_memberemail"],
					'parentCommentID' => NULL,
				]);
			} break;

			/******** like a comment **************/
			case "c_like":
			{
				$insertOneResult = $collectionCL->insertOne([
					'memberEmail' => $_SESSION["login_memberemail"],
					'commentID' => $_POST['id'],
				]);	
			} break;
			
			/******** unlike a comment **************/
			case "c_unlike":
			{
				$deleteResult = $collectionCL->deleteMany([
					'memberEmail' => $_SESSION['login_memberemail'],
					'commentID' => $_POST['id'],
				]);
			} break;

			/******** comment reply **************/

			case "CommentReply":
			{
				$insertOneResult = $collectionComment->insertOne([
					'commentBody' => $_POST['commentReply'],
					'postID' => $_POST['postid'],
					'commentDate' => date('m/d/Y h:i:s a', time()),
					'memberEmail' => $_SESSION["login_memberemail"],
					'parentCommentID' => $_POST["commentid"],
				]);
			}

		}
	
	}


} catch(MongoCursorException $e)
{
		//exception 
}


?>