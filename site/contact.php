<?php
	include_once('./includes/security.php');
	// Set up Sessions
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php
			include_once('./includes/loader.php');			
		?>		
	</head>
	<body class="container-fluid">
		<!-- START CONTENT -->
		<?php include ('./includes/header.php');?>	
		<div class="row">
			<div class="col-sm-12 image-bg" style="padding:10px 0 0 0;margin: 0;">				
				<div class="col-sm-10 col-sm-offset-1 standard-heading">
					Contact Cleexa
				</div>
				<div class="col-sm-10 col-sm-offset-1 standard" style="margin-bottom: 10px;">
					<form class="form-horizontal pad" action="./includes/contactmail.php" method="POST">
						<?php
							if(isset($_SESSION['username'])){
								// User is logged in, get user Id
								echo '<input type="hidden" name="userId" value="'.$user_id.'"/>';
								
								$userInfo_set = mysqli_query($secure_db,"SELECT * FROM `cleexa_members` WHERE `user_id` = '$user_id'");
								$userInfo = mysqli_fetch_array($userInfo_set);
								
								if(isset($_SESSION['company_id'])){
									// User is also a business, get Business Id
									echo '<input type="hidden" name="businessId" value="'.$_SESSION['company_id'].'"/>';
									
									$vendorId = $_SESSION['company_id'];
									$vendorInfo_set = mysqli_query($secure_db,"SELECT * FROM `cleexa_vendors` WHERE `vendor_id` = '$vendorId'");
									$vendorInfo = mysqli_fetch_array($vendorInfo_set);
								}
							}
						?>
						
						<div class="form-group">
							<label for="name">Name *</label>
							<?php
								if (isset($_SESSION['company_id'])){
									// Company user
									$preName = $userInfo['forename'].' '.$userInfo['surname'].' ('.$vendorInfo['company'].')';
									echo '<input class="form-control" id="name" required name="name" placeholder = "Name" type="text" value ="'.$preName.'"/>';
								} elseif (isset($_SESSION['username']) && !isset($_SESSION['company_id'])){
									// Regular user
									$preName = $userInfo['forename'].' '.$userInfo['surname'];
									echo '<input class="form-control" id="name" required name="name" placeholder = "Name" type="text" value ="'.$preName.'"/>';
								} else {
									// Not logged in
									echo '<input class="form-control" id="name" required name="name" placeholder = "Name" type="text"/>';
								}
							?>
							
							<label for="email">Email *</label>
							<?php
								if (isset($_SESSION['company_id'])){
									// Company user
									$preEmail = $userInfo['email'];
									echo '<input placeholder="email@domain.com" class="form-control" id="email" name="email" required type="email" value ="'.$preEmail.'"/>';
								} elseif (isset($_SESSION['username']) && !isset($_SESSION['company_id'])){
									// Regular user
									$preEmail = $userInfo['email'];
									echo '<input placeholder="email@domain.com" class="form-control" id="email" name="email" required type="email" value ="'.$preEmail.'"/>';
								} else {
									// Not logged in
									echo '<input placeholder="email@domain.com" class="form-control" id="email" name="email" required type="email"/>';
								}
							?>								
						</div>
						<hr>
						<div class="form-group">
							<label for="enquiryType">Enquiry</label>
							<select name="enquiryType" id="enquiryType" class="form-control">
								<option value="1">General</option>
								<option value="2">Accounts</option>
								<option value="3">Disputes</option>
								<option value="4">Complaints</option>
								<option value="5">Report User / Vendor</option>
								<option value="6">Other</option>
							</select>
						</div>
						<hr>
						<div class="form-group">
							<label for="subject">Subject *</label>
							<input class="form-control" id="subject" required name="subject" placeholder = "Subject" type="text"/>
							
							<label for="message">Message *</label>
							<textarea class="form-control" rows="9" name="message" id="message" placeholder="Message"></textarea>
						</div>
						<hr>
						<div class="form-group">
							<div class="col-sm-5">
								<input class="btn btn-lg btn-block btn-success" type="submit" value="Submit"/>
							</div>
							<div class="col-sm-5 col-sm-offset-2">
								<input class="btn btn-lg btn-block btn-danger" type="reset" value="Reset"/>
							</div>
						</div>
					</form>
				</div>				
			</div>
		</div>
		<?php include ('./includes/footer.php');?>		
		<!-- END CONTENT -->
	</body>
</html>