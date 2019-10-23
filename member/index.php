<?php 
	include __DIR__ . '/../header.php';
?>

<div class="bgMain"> 
			<form action="create.php" method="post">
				<div class="form-row">
					<div class="col-md-8 mb-2">
					      <label for="txtScreenName">Screen name</label>
					      <input type="text" class="form-control" id="txtScreenName" name="txtScreenName"  required>
					</div>
				</div>	    
				<div class="form-row">
				    <div class="col-md-4 mb-2">
				      <label for="txtFirstName">First name</label>
				      <input type="text" class="form-control" id="txtFirstName"  name="txtFirstName" required>
				    </div>
				    <div class="col-md-4 mb-2">
				      <label for="txtLastName">Last name</label>
				      <input type="text" class="form-control" id="txtLastName" name="txtLastName"  required>
				    </div>
			    </div>

			    <div class="form-row">
				    <div class="col-md-4 mb-2">
				      <label for="txtEmail">Email address</label>
				      <input type="email" class="form-control" id="txtEmail"  name="txtEmail" required>
				    </div>
				    <div class="col-md-4 mb-2">
				      <label for="txtPassword">Password</label>
				      <input type="Password" class="form-control" id="txtPassword"  name="txtPassword" required>
				    </div>
			    </div>

			    

			    <div class="form-row">
				    <div class="col-md-4  mb-2">
			    		<label for="txtBirthDate">Birthdate</label>
			    		<input class="form-control" type="date"  pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}T[0-9]{2}:[0-9]{2}"  value="<?php echo date('Y-m-d'); ?>" id="txtBirthDate" name="txtBirthDate">
			    	</div>
				    <div class="col-md-4 mb-2">
				      <label for="cbGender">Gender</label>
					       <select class="custom-select mr-sm-2" id="cbGender" name="cbGender">
	
						        <option value="Male">Male</option>
						        <option value="Female">Female</option>
					      </select>
				    </div>
			    </div>

			    <div class="form-row">
			    	
			    	<div class="col-md-4 mb-2">
				      <label for="txtStatus">Status</label>
				      <select class="custom-select mr-sm-2" id="cbStatus" name="cbStatus">
						        <option value="Active">Active</option>
						        <option value="InAcitve">InAcitve</option>
					      </select>
				    </div>

				    <div class="col-md-4 mb-2">
				      <label for="cbVisibility">Visibility</label>
					       <select class="custom-select mr-sm-2" id="cbVisibility" name="cbVisibility">
		
						        <option value="private">private</option>
						        <option value="friends-only">friends-only</option>
						        <option value="everyone">everyone</option>
					      </select>
				    </div>
			    </div>

			    <div class="form-row">
					<div class="col-md-8 mb-2">
					      <label for="txtLocation">Location</label>
					      <input type="text" class="form-control" id="txtLocation" name="txtLocation" required>
					    </div>
				</div>
			 
			 	<input type="hidden" class="form-control"  name="txt_crud" value="1">

			  <button class="btn" type="submit">Register</button>
			</form>
		</div>

<?php 
	include __DIR__ . '/../footer.php';
?>