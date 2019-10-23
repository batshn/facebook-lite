<?php 
	include('header.php') 
?>


<?php
	session_start();
	if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] != true){
	    header("location: index.php");
	    exit;
	}
	//require_once "dbconfig.php";
	include_once __DIR__ . '/connection.php';
	$collection = $client->FacebookMongo->Members;

	$member = $collection->findOne([
		'MemberEmail' => $_SESSION['login_memberemail']
	  ]);

	$txtEmail=$member['MemberEmail'];
	$txtPassword=$member['LoginPassword'];
	$txtFirstName=$member['FirstName'];
	$txtLastName=$member['LastName'];
	$txtScreenName=$member['ScreenName'];
	$txtBirthDate=$member['BirthDate'];
	$cbGender=$member['Gender'];
	$cbStatus=$member['Status'];
	$txtLocation=$member['mLocation'];
	$cbVisibility=$member['Visibility'];

?>

<div class="bgMain"> 
			<form action="member/update.php" method="post">
				<div class="form-row">
					<div class="col-md-8 mb-2">
					      <label for="txtScreenName">Screen name</label>
					      <input type="text" class="form-control" id="txtScreenName" name="txtScreenName" 
					      value="<?php echo $txtScreenName; ?>" required>
					    </div>
				</div>	    
				<div class="form-row">
				    <div class="col-md-4 mb-2">
				      <label for="txtFirstName">First name</label>
				      <input type="text" class="form-control" id="txtFirstName" value="<?php echo $txtFirstName; ?>" name="txtFirstName" required>
				    </div>
				    <div class="col-md-4 mb-2">
				      <label for="txtLastName">Last name</label>
				      <input type="text" class="form-control" id="txtLastName" name="txtLastName" value="<?php echo $txtLastName; ?>" required>
				    </div>
			    </div>

			    <div class="form-row">
				    <div class="col-md-4 mb-2">
				      <label for="txtEmail">Email address</label>
				      <input type="text" class="form-control" id="txtEmail" value="<?php echo $txtEmail; ?>" name="txtEmail" required >
				    </div>
				    <div class="col-md-4 mb-2">
				      <label for="txtPassword">Password</label>
				      <input type="Password" class="form-control" id="txtPassword" value="<?php echo $txtPassword; ?>" name="txtPassword" required>
				    </div>
			    </div>

			    

			    <div class="form-row">
				    <div class="col-md-4  mb-2">
			    		<label for="txtBirthDate">Birthdate </label>
			    	<!--	<input class="form-control" type="date"  pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}"  value="<?php echo date('Y-m-d'); ?>" id="txtBirthDate" name="txtBirthDate"> -->
						<input class="form-control" type="date"  pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}"  value="<?php echo date('Y-m-d', strtotime($txtBirthDate));  ?>" id="txtBirthDate" name="txtBirthDate"> 
			    	</div>
				    <div class="col-md-4 mb-2">
				      <label for="cbGender">Gender</label>
					       <select class="custom-select mr-sm-2" id="cbGender" name="cbGender">
						     <!--   <option value="Male">Male</option>
						        <option value="Female">Female</option> -->
							<?php
							//echo $cbStatus;
								switch ($cbGender) {
									case "Male":
										echo '<option value="Male" selected>Male</option>';
										echo '<option value="Female">Female</option>';
										break;
									case "Female":
										echo '<option value="Male">Male</option>';
										echo '<option value="Female" selected>Female</option>';
										break;
									default:
										echo '<option value="Male">Male</option>';
										echo '<option value="Female">Female</option>';
								}
							?>
					      </select>
				    </div>
			    </div>

			    <div class="form-row">
			    	
			    	<div class="col-md-4 mb-2">
				      <label for="txtStatus">Status</label>
				      <select class="custom-select mr-sm-2" id="cbStatus" name="cbStatus">
						      <!--  <option value="1">Active</option>
						        <option value="2">InAcitve</option> -->

								<?php
								
								switch ($cbStatus) {
									case "Active":
										echo '<option value="Active" selected>Active</option>';
										echo '<option value="InActive">InActive</option>';
										break;
									case "InActive":
										echo '<option value="Active">Active</option>';
										echo '<option value="InActive" selected>InActive</option>';
										break;
									default:
										echo '<option value="Active">Active</option>';
										echo '<option value="InActive">InActive</option>';
								}
								?>
					      </select>
				    </div>

				    <div class="col-md-4 mb-2">
				      <label for="cbVisibility">Visibility</label>
					       <select class="custom-select mr-sm-2" id="cbVisibility" name="cbVisibility">
		
						       <!-- <option value="private">private</option>
						        <option value="friends-only">friends-only</option>
						        <option value="everyone">everyone</option> -->

								<?php
								switch ($cbVisibility) {
									case "private":
										echo '<option value="private" selected>private</option>';
										echo '<option value="friends-only">friends-only</option>';
										echo '<option value="everyone">everyone</option>';
										break;
									case "friends-only":
										echo '<option value="private">private</option>';
										echo '<option value="friends-only"  selected>friends-only</option>';
										echo '<option value="everyone">everyone</option>';
										break;
									case "everyone":
										echo '<option value="private">private</option>';
										echo '<option value="friends-only">friends-only</option>';
										echo '<option value="everyone" selected>everyone</option>';
										break;
									default:
										echo '<option value="private">private</option>';
										echo '<option value="friends-only">friends-only</option>';
										echo '<option value="everyone">everyone</option>';
								}
								?>


					      </select>
				    </div>
			    </div>

			    <div class="form-row">
					<div class="col-md-8 mb-2">
					      <label for="txtLocation">Location</label>
					      <input type="text" class="form-control" id="txtLocation" name="txtLocation" value="<?php echo $txtLocation; ?>" required>
					    </div>
				</div>
			 
			 	<input type="hidden" class="form-control"  name="txt_crud" value="2">

			  <button class="btn" type="submit" name="update" id="update">Update</button>
			  <a href="main.php" class="btn btn-success" >Back </a>

			</form>
		</div>

<?php include 'footer.php' ?>