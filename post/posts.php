<?php
	
session_start();

include_once __DIR__ . '/../connection.php';
$collection = $client->FacebookMongo->Posts;
$colFriends = $client->FacebookMongo->FriendWith;
$output="";

try
{
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

	//	$memberIDS[]=$_SESSION["login_memberemail"];

		$options = [
			'allowDiskUse' => TRUE
		];

		$pipeline = [
			[
				'$project' => [
					'_id' => 0,
					'Posts' => '$$ROOT'
				]
			], [
				'$lookup' => [
					'localField' => 'Posts.memberEmail',
					'from' => 'Members',
					'foreignField' => 'MemberEmail',
					'as' => 'Members'
				]
			], [
				'$unwind' => [
					'path' => '$Members',
					'preserveNullAndEmptyArrays' => FALSE
				]
			], [
				'$match' => [
					'$or' => [
						[
							'$and' => [
								[
									'Members.Visibility' => [ '$ne' => 'private' ]
								],
								[
									'Posts.memberEmail' => array('$in'=>  $memberIDS)
								]
							]
						],
						[
							'Members.MemberEmail' => $_SESSION["login_memberemail"]
						]
					]
				]
			],[
				'$sort' => [
					'Posts.postDate' => -1
				]
			], [
				'$project' => [
					'Posts._id' => '$Posts._id',
					'Posts.postBody' => '$Posts.postBody',
					'Posts.postDate' => '$Posts.postDate',
					'Members.FirstName' => '$Members.FirstName',
					'Members.LastName' => '$Members.LastName',
					'Members.Status' => '$Members.Status',
					'Posts.memberEmail' => '$Posts.memberEmail',
					'_id' => 0
				]
			]
		];

		
		$cursor = $collection->aggregate($pipeline, $options);

		foreach ($cursor as $row) 
			{

			    $LikeCnt=countOfPostLike($row['Posts']->_id,$client);
				$IsLiked=isLike($row['Posts']->_id,$_SESSION["login_memberemail"] ,$client);

				$memberActive="";
				$likeName="";

				if ($row['Members']->Status=='Active') $memberActive="postedMember";
				else $memberActive="postedMemberInActive";

				$output .= '<div class='. $memberActive .'>'. $row['Members']->FirstName .' '. $row['Members']->LastName .'</div>';	
				$output .= '<div class="postBody">'. $row['Posts']->postBody .'</div>';

				if ($LikeCnt>0) $output .= '<div class="postBar"> <span class="fa fa-thumbs-up"> </span> '. $LikeCnt ;
				else $output.= '<div class="postBar">';
				
				if($IsLiked>0) $likeName = "unlike";
				else $likeName = "like";

				$output .= '<button type="button" name='. $likeName .' id='. $row['Posts']->_id .' class="'. $likeName .' btn btn-sm btn-light">Like</button></div>';

			/*****************select comments****************************/
				$output .= '<div class="commentBox">';
				$output .='<div class="input-group mb-3">
									<input type="text" class="form-control input-sm" placeholder="Write a comment"  name="cb'. $row['Posts']->_id .'" id="cb'. $row['Posts']->_id .'" style="border-top-left-radius: 30px; border-bottom-left-radius: 30px;">
									<div class="input-group-append">
									<button class="CommentAdd btn btn-info btn-sm" type="button"  style="border-top-right-radius: 20px; border-bottom-right-radius: 20px;" name="Add" id='. $row['Posts']->_id .' >Add</button>
									</div>
								</div>
							</div>';			
				$output .=fetchComments($client,$row['Posts']->_id);
		}
		echo $output;
	}
} catch(MongoCursorException $e)
{
	print_r($e);
}

// fetching comments
function fetchComments($client,$postID) {
				$outputSub='';
				$collectionComments = $client->FacebookMongo->Comments;

				$options = [
					'allowDiskUse' => TRUE
				];

				$pipeline = [
					[
						'$project' => [
							'_id' => 0,
							'Comments' => '$$ROOT'
						]
					], [
						'$lookup' => [
							'localField' => 'Comments.memberEmail',
							'from' => 'Members',
							'foreignField' => 'MemberEmail',
							'as' => 'Members'
						]
					], [
						'$unwind' => [
							'path' => '$Members',
							'preserveNullAndEmptyArrays' => FALSE
						]
					], [
						'$match' => [
						//	'Comments.postID' => (string)$postID,
						//	$fieldName => $fieldValue
							'Comments.postID' => (string)$postID,
							'Comments.id' => NULL
						]
					], [
						'$sort' => [
							'Comments.commentDate' => -1
						]
					], [
						'$project' => [
							'Members.FirstName' => '$Members.FirstName',
							'Members.LastName' => '$Members.LastName',
							'Members.Status' => '$Members.Status',
							'Comments._id' => '$Comments._id',
							'Comments.commentBody' => '$Comments.commentBody',
							'Comments.commentDate' => '$Comments.commentDate',
							'Comments.memberEmail' => '$Comments.memberEmail',
							'_id' => 0
						]
					]
				];


				$cursor = $collectionComments->aggregate($pipeline, $options);

				$outputSub .= '<div class="comments">';
				foreach ($cursor as $rowSub) 
				{
					$isActive="commentMemberInActive";
					$cntLikes=countOfCommentLike($rowSub['Comments']->_id,$client);
					$isLiked=isLikeComment($rowSub['Comments']->_id,$_SESSION["login_memberemail"] ,$client);
					$likeID="c_like";

					$outputSub .= '<div class="commentItem">';

					if($rowSub['Members']->Status=='Active') $isActive="commentMember";
					$outputSub .= '<span class="'.$isActive.'">'. $rowSub['Members']->FirstName .' '. $rowSub['Members']->LastName .' </span>';
					
					$outputSub .= ' <span class="commentBody">'. $rowSub['Comments']->commentBody .'</span> <br>';
					
					if ($cntLikes>0)
						$outputSub .= '<span class="fa fa-thumbs-up fa-thumbs-up-com"> '. $cntLikes .'</span> ';
							
					
					$outputSub .= '</div>';

					if($isLiked>0) $likeID="c_unlike";
					$outputSub .= '<button type="button" name="'.$likeID.'" id='.$rowSub['Comments']->_id .' class="'.$likeID.' btn btn-sm btn-link">Like</button>';
					
			
					$outputSub .='<div class="commentItemReply" style="margin-left:20px; padding-bottom:15px;">';
					$outputSub .='<div class="input-group" >
						  				<input type="text" class="form-control input-sm" style="font-size: 12px; border-top-left-radius: 30px; border-bottom-left-radius: 30px;" placeholder="Write a reply"  name="cbsub' .$rowSub['Comments']->_id .'" id="cbsub' .$rowSub['Comments']->_id .'">
						  				<div class="input-group-append">
							    			<button class="CommentReply btn btn-warning btn-sm" style="font-size: 12px; border-top-right-radius: 20px; border-bottom-right-radius: 20px;"  type="button" name="Add" id=' .$rowSub['Comments']->_id .' >Add</button>
							  			</div>
					  			  </div>
							</div>';
					

					/*****************select comments****************************/
					$outputSub .=get_reply_comment($client,$rowSub['Comments']->_id,20);
					//$outputSub .=fetchComments($conn,$rowSub['COMMENTID'],$marginleft+20);
					// $outputSub .=fetchComments($client,NULL,'Comments.parentCommentID',(string)$rowSub['Comments']->_id,$marginleft+20);
				}
				$outputSub .= '</div>';

				return $outputSub;
				
}

// fetching sub comments
function get_reply_comment($client,$parent_cid,$marginleft)
{
	$outputSub='';
	
	$collectionComments = $client->FacebookMongo->Comments;

	$options = [
		'allowDiskUse' => TRUE
	];

	$pipeline = [
		[
			'$project' => [
				'_id' => 0,
				'Comments' => '$$ROOT'
			]
		], [
			'$lookup' => [
				'localField' => 'Comments.memberEmail',
				'from' => 'Members',
				'foreignField' => 'MemberEmail',
				'as' => 'Members'
			]
		], [
			'$unwind' => [
				'path' => '$Members',
				'preserveNullAndEmptyArrays' => FALSE
			]
		], [
			'$match' => [
				'Comments.parentCommentID' => (string)$parent_cid,
			]
		], [
			'$sort' => [
				'Comments.commentDate' => -1
			]
		], [
			'$project' => [
				'Members.FirstName' => '$Members.FirstName',
				'Members.LastName' => '$Members.LastName',
				'Members.Status' => '$Members.Status',
				'Comments._id' => '$Comments._id',
				'Comments.commentBody' => '$Comments.commentBody',
				'Comments.commentDate' => '$Comments.commentDate',
				'Comments.memberEmail' => '$Comments.memberEmail',
				'_id' => 0
			]
		]
	];			


	$cursor = $collectionComments->aggregate($pipeline, $options);

	foreach ($cursor as $rowSub) 
	{
		$isActive="commentMemberInActive";
		$cntLikes=countOfCommentLike($rowSub['Comments']->_id,$client);
		$isLiked=isLikeComment($rowSub['Comments']->_id,$_SESSION["login_memberemail"] ,$client);
		$likeID="c_like";

		$outputSub .= '<div class="commentItem" style="margin-left:'.$marginleft.'px">';

		if ($rowSub['Members']->Status=='Active') $isActive="commentMember";
		$outputSub .= '<span class="'.$isActive.'">'. $rowSub['Members']->FirstName .' '. $rowSub['Members']->LastName .' </span>';
		
		$outputSub .= ' <span class="commentBody">'. $rowSub['Comments']->commentBody .'</span> <br>';
					
		if ($cntLikes>0)
			$outputSub .= '<span class="fa fa-thumbs-up fa-thumbs-up-com"> '. $cntLikes .'</span> ';
			
		$outputSub .= '</div>';

		if($isLiked>0) $likeID="c_unlike";
		
		$outputSub .= '<button type="button" style="margin-left:'.$marginleft.'px" name="'.$likeID.'" id=' .$rowSub['Comments']->_id .' class="'.$likeID.' btn btn-sm btn-link">Like</button>';

		$outputSub .='<div class="commentItemReply" style="margin-left:'.$marginleft.'px; padding-bottom:15px;">';
					$outputSub .='<div class="input-group" >
						  				<input type="text" class="form-control input-sm" style="font-size: 12px; border-top-left-radius: 30px; border-bottom-left-radius: 30px;" placeholder="Write a reply"  name="cbsub' .$rowSub['Comments']->_id .'" id="cbsub' .$rowSub['Comments']->_id .'">
						  				<div class="input-group-append">
							    			<button class="CommentReply btn btn-warning btn-sm" style="font-size: 12px; border-top-right-radius: 20px; border-bottom-right-radius: 20px;"  type="button" name="Add" id=' .$rowSub['Comments']->_id .' >Add</button>
							  			</div>
					  			  </div>
						</div>';

		/*****************select reply comments****************************/
		$outputSub .=get_reply_comment($client,$rowSub['Comments']->_id,$marginleft+20);
	}

	return $outputSub;
}


//** count of post likes   */
function countOfPostLike($postID, $client)
{
	$colPL = $client->FacebookMongo->PostLikes;
	$cntLikes = $colPL->count(['postID' => (string) $postID]);

	return $cntLikes;
}


//** check whether post is liked by Member/
function isLike($postID, $memberEmail, $client)
{
	
	$colPL = $client->FacebookMongo->PostLikes;
	$isLiked = $colPL->count(['postID' => (string)$postID, 'memberEmail'=> (string)$memberEmail ]);
	return $isLiked ;
}


//count of comment likes   
function countOfCommentLike($commentID, $client)
{
	$colCL = $client->FacebookMongo->CommentLikes;
	$cntLikes = $colCL->count(['commentID' => (string) $commentID]);

	return $cntLikes;
}


//check whether comment is liked by Member
function isLikeComment($commentID,$memberEmail ,$client)
{	
	$colCL = $client->FacebookMongo->CommentLikes;
	$isLiked = $colCL->count(['commentID' => (string)$commentID, 'memberEmail'=> (string)$memberEmail ]);
	return $isLiked ;
}

?>