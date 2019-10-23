
$(document).ready(function(){
	populatePost();
	/*********** fetching all posts******************/
	function populatePost()
	{
		var action="select";
		$.ajax({
			url: "post/posts.php",
			method: "POST",
			data:{action:action},
			success:function(data){
				$('#txtNewPost').val('');
				$('#action').text("Post");
				$('#posts').html(data);
			}
		});	
	}

	/*********** calling the method of adding a post ******************/
	$('#action').click(function(){  
	    var postBody = $('#txtNewPost').val();  
	    //var id = $('#user_id').val();  
	    var action = $('#action').text();  
	    if(postBody != '')  
	    {  
	        $.ajax({  
	            url : "post/post_crud.php",  
	            method:"POST",  
	            data:{postBody:postBody, action:action},  
	            success:function(data){  
	                //alert(data);  
	                populatePost(); 
	            }  
	        });  
	    }  
	    else  
	    {  
	        alert("Please write a post!");  
	    }  
	});  

	/*********** like a post  ******************/
	  $(document).on('click', '.like', function(){  
           var id = $(this).attr("id");  
		   var action = "like";  
                $.ajax({  
                     url:"post/post_crud.php",  
                     method:"POST",  
                     data:{id:id, action:action},  
                     success:function(data)  
                     {  
                          populatePost();
                          //alert(data);  
                     }  
                })  
      });

      /*********** dislike a post ******************/
	  $(document).on('click', '.unlike', function(){  
           var id = $(this).attr("id");  
		   var action = "unlike";  
                $.ajax({  
                     url:"post/post_crud.php",  
                     method:"POST",  
                     data:{id:id, action:action},  
                     success:function(data)  
                     {  
                          populatePost(); 
                          //alert(data);  
                     }  
                })  
      });

      /*********** like a comment  ******************/
	  $(document).on('click', '.c_like', function(){  
           var id = $(this).attr("id");  
		   var action = "c_like";  
                $.ajax({  
                     url:"post/post_crud.php",  
                     method:"POST",  
                     data:{id:id, action:action},  
                     success:function(data)  
                     {  
                          populatePost(); 
                          //alert(data);  
                     }  
                })  
      });

      /*********** dislike a comment ******************/
	  $(document).on('click', '.c_unlike', function(){  
           var id = $(this).attr("id");  
		   var action = "c_unlike";  
                $.ajax({  
                     url:"post/post_crud.php",  
                     method:"POST",  
                     data:{id:id, action:action},  
                     success:function(data)  
                     {  
                          populatePost();
                          //alert(data);  
                     }  
                })  
      });


	  /*********** add a comment ******************/
	  $(document).on('click', '.CommentAdd', function(){  
	  	  // var postBody = $('#txtNewPost').val();  

           var postid = $(this).attr("id");  
           var cmName='#cb';
           var cmBoxName = cmName.concat(postid);
           var commentBody = $(cmBoxName).val(); 
		   var action = "CommentAdd"; 
		 //  alert(commentBody); 
		   if(commentBody != '')  
		    {  
                $.ajax({  
                     url:"post/post_crud.php",  
                     method:"POST",  
                     data:{postid:postid,commentBody:commentBody, action:action},  
                     success:function(data)  
                     {  
                          populatePost();
                         // $(cmBoxName).val('');
                     }  
                })  
            }
            else  
		    {  
		        alert("Please write a comment!");  
		    }  
      });


 	/*********** reply a comment ******************/
	  $(document).on('click', '.CommentReply', function(){  
           var commentid = $(this).attr("id");  
           var cmName='#cbsub';
           var cmBoxName = cmName.concat(commentid);
           var commentReply = $(cmBoxName).val(); 
		   var action = "CommentReply"; 
		 //  alert(commentReply); 
		  // alert(commentid); 
		   if(commentReply != '')  
		    {  
                $.ajax({  
                     url:"post/post_crud.php",  
                     method:"POST",  
                     data:{commentid:commentid,commentReply:commentReply, action:action},  
                     success:function(data)  
                     {  
                          populatePost();
                         //   alert($(cmBoxName).val()); 
                     }  
                })  
            }
            else  
		    {  
		        alert("Please write a reply!");  
		    }  
      });
		

    populateFrList();
  /*********** fetching all friendrequest list******************/
  function populateFrList()
  {
    var action="pending";
    $.ajax({
      url: "friendRequest/pendingList.php",
      method: "POST",
      data:{action:action},
      success:function(data){
        $('#friendRequest').html(data);
      }
    }); 
  }

  /**************Accept friendrequest*************************/
  $(document).on('click', '.fr_accept', function(){  
       var sender_id = $(this).attr("id");  
       var action = "Accept";  
     //  alert(sender_id); 
                $.ajax({  
                     url:"friendRequest/acceptRequest.php",  
                     method:"POST",  
                     data:{sender_email:sender_id, action:action},  
                     success:function(data)  
                     {  
                          populateFrList();
                          populateFrListAccepted();
                        //  alert(data);  
                          populatePost();
                     }  
                })  
      });

  
   populateFrListAccepted();
  /*********** fetching all friendships******************/
  function populateFrListAccepted()
  {
    var action="select";
    $.ajax({
      url: "friendRequest/acceptedList.php",
      method: "POST",
      data:{action:action},
      success:function(data){
        $('#friendList').html(data);
      }
    }); 
  }

  /**************Reject friendrequest*************************/
  $(document).on('click', '.fr_reject', function(){  
       var sender_id = $(this).attr("id");  
       var action = "Reject";  
     //  alert(sender_id); 
                $.ajax({  
                     url:"friendRequest/rejectFriend.php",  
                     method:"POST",  
                     data:{sender_email:sender_id, action:action},  
                     success:function(data)  
                     {  
                          populateFrListAccepted();
                          populateFrList();
                          populatePost();
                         // alert(data);  
                     }  
                })  
      });

/**************search a friend*************************/
  load_data();
  function load_data(query)
       {
        //alert(query);
          $.ajax({
             url:"friendRequest/selectFriend.php",
             method:"POST",
             data:{query:query},
             success:function(data)
             {
              $('#friendSearchList').html(data);
             }
          });
       }

       $('#txtSrMemberName').keyup(function(){
          var search = $(this).val();
          if(search != '')
          {
           load_data(search);
          }
          else
          {
           load_data();
          }
       });

       /**************request a friend*************************/
       $(document).on('click', '.fr_request', function(){  
           var receiver_id = $(this).attr("id");  
           var action = "Request";  
                $.ajax({  
                     url:"friendRequest/sendRequest.php",  
                     method:"POST",  
                     data:{receiver_email:receiver_id, action:action},  
                     success:function(data)  
                     {  
                          alert(data);  
                        //  $('#txtSrMemberName').val('');
                          load_data();
                     }  
                })  
         });


});

