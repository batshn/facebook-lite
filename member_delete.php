<?php 
include('header.php') 
?>
<?php
	session_start();
?>

<div class="row" style="margin-top: 100px;">
	
		<div class="col-md-3">
		</div>
		<div class="col-md-6">
		<?php echo $_SESSION['error_msg']; ?>
			<br>
			<form action="member/delete.php" method="post">
				<input type="hidden" class="form-control"  name="txt_crud" value="3">
				Are you sure to delete your account?  
			    <button class="btn btn-danger" type="submit" name="update" id="update">Yes, delete</button>
				<a href="main.php" class="btn btn-warning" >Back </a>
			</form>
			<?php $_SESSION['error_msg']=''; ?>
		</div>
		<div class="col-md-3">
		</div>

</div>