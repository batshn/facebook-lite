<?php
	// Initialize the session
	session_start();
	$_SESSION['error_msg']=''; 
	//Check if the user is logged in, if not then redirect him to login page
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
		header("location: index.php");
		exit;
}

?>

<?php include('header.php') ?>

	<div class="row">
		<div class="col-md-11">
			Hello ! <?php echo htmlspecialchars($_SESSION["login_screenname"]); ?>
		</div>
		<div class="col-md-1">
			<div class="btnLogout" >
				<a href="logout.php" class="btn btn-link" style="font-weight: bolder; font-size: 12px;">Logout</a>
			</div>
		</div>
	</div>
		
	<div class="row">

		<!--  Begin left section -->
		<div class="col-md-3">
			<div class="leftSection" style="padding-left: 5x; padding-top: 30px;">
				<div><a href="member_update.php" class="btn btn-link" style="font-weight: bolder; font-size: 14px; color: #1F2020;">Update profile </a></div>
				<div><a href="member_delete.php" class="btn btn-link" style="font-weight: bolder; font-size: 14px; color: #1F2020;">Delete account</a></div>
			</div>
			<div>
				<?php include 'member_search.php';?>
			</div>
	    </div>
	    <!--  End left section -->
		
		<!--  Begin middle section -->
		<div class="col-md-6">
			<div id="newPostBox">
				<div class="npbTitle">
					Write a post 
				</div>
				<div class="npbBody">
				<!--	<form action="post_process.php" method="post"> -->
						<textarea name="txtNewPost" id="txtNewPost" rows="3" class="form-control btn-block" required></textarea>
						<button type="submit" class="btn btn-info btn-sm btn-block" name="action" id="action">Post</button>
				<!--	</form> -->
				</div>
			</div>

			<!--  Begin post section -->
			<div id="posts">
				
			</div>
			<!--  End post section -->


		</div>
		<!--  End middle section -->
	
		<!--  Begin right section -->
		<div class="col-md-3">
			<?php include 'friends.php';?>
		</div>
		<!--  End right section -->

	</div>


	<div id="bgFooter">
		.
	</div>
<?php include 'footer.php' ?>

