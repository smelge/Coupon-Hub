<?php
	include_once('./includes/security.php');
	// Set up Sessions
	$pageTitle = 'The Coupon Hub';
	$metaKeywords = '';
	
	// Check for cookie
	if (isset($_COOKIE['CH-userdata'])){
		// Cookie Exists
		// Show relevant frontpage
		$indexContent = $_COOKIE['CH-userdata'];
		// Renew cookie
		setcookie('CH-userdata',$_COOKIE['CH-userdata'],time() + (86400 * 30), '/');
	} else {
		// Cookie doesn't exist
		// check if selection made
		if (isset($_GET['ic'])){
			// Selection made
			// Display relevant page
			if ($_GET['ic'] == 'v'){
				$indexContent = 'vendor-index';
				setcookie('CH-userdata','vendor-index',time() + (86400 * 30), '/');
			} elseif ($_GET['ic'] == 'c'){
				$indexContent = 'user-index';
				setcookie('CH-userdata','user-index',time() + (86400 * 30), '/');			
			} else {
				header('Location: ./welcome.php');
			}
		} else {
			// Selection not made
			$showCookies = 'y';
			// Display selection page
			header('Location: ./welcome.php');
		}	
	}
	
	if($_COOKIE['CH-userdata'] == 'vendor-index' || $_GET['ic'] == 'v'){
		$indexBg = 'vendor-body';
		$indexNav = 'ch-vendor-header';
	} else {
		$indexBg = 'customer-body';
		$indexNav = 'ch-header';
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php
			include_once('./includes/meta.php');			
			include_once('./includes/scripts.php');			
		?>
		<link rel="stylesheet" type="text/css" href="slick/slick.css"/>
		<link rel="stylesheet" type="text/css" href="slick/slick-theme.css"/>
		<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<script src="https://code.jquery.com/jquery-2.2.0.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
		<script src="./slick/slick.js" type="text/javascript" charset="utf-8"></script>
		<?php
			if ($showCookies == 'y'){
				include ('./includes/cookies.php');
			}
		?>
	</head>
	<body class="container-fluid <?php echo $indexBg;?>">
		<!-- START CONTENT -->
		<?php 
			include ('./includes/'.$indexNav.'.php');
		?>
		<div class="row toprow">		
			<div class="col-sm-10 col-sm-offset-1 standard">
				<div class="col-sm-12 form-heading">
					Content Title 1
				</div>
				<div class="col-sm-12">
					Content Block 1
				</div>
			</div>
		</div>	
		<div class="row" style="margin-top:20px;">		
			<div class="col-sm-10 col-sm-offset-1 standard">
				<div class="col-sm-12 form-heading">
					Content Title 2
				</div>
				<div class="col-sm-12">
					Content Block 2
				</div>
			</div>
		</div>
		<div class="row" style="margin-top:20px;">		
			<div class="col-sm-10 col-sm-offset-1 standard">
				<div class="col-sm-12 form-heading">
					Content Title 3
				</div>
				<div class="col-sm-12">
					Content Block 3
				</div>
			</div>
		</div>
		<div class="row" style="margin-top:20px;">		
			<div class="col-sm-10 col-sm-offset-1 standard">
				<div class="col-sm-12 form-heading">
					Content Title 4
				</div>
				<div class="col-sm-12">
					Content Block 4
				</div>
			</div>
		</div>
		<!-- Contact -->
		<div class="row" style="margin-top:20px;">		
			<div class="col-sm-10 col-sm-offset-1 standard">
				<div class="col-sm-12 form-heading">
					Contact The Coupon Hub
				</div>
				<div class="col-sm-12">
					<?php
						if(isset($_SESSION['username'])){
							// User is logged in, get user Id
							echo '<input type="hidden" name="userId" value="'.$user_id.'"/>';
							
							$userInfo_set = mysqli_query($secure_db,"SELECT * FROM `members` WHERE `user_id` = '$user_id'");
							$userInfo = mysqli_fetch_array($userInfo_set);
									
							if(isset($_SESSION['company_id'])){
								// User is also a business, get Business Id
								echo '<input type="hidden" name="businessId" value="'.$_SESSION['company_id'].'"/>';
											
								$vendorId = $_SESSION['company_id'];
								$vendorInfo_set = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `vendor_id` = '$vendorId'");
								$vendorInfo = mysqli_fetch_array($vendorInfo_set);
							}
						}
					?>
					<form class="form-horizontal pad" action="./includes/contactmail.php" method="POST">
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
						<div class="form-group">
							<label for="subject">Subject *</label>
							<input class="form-control" id="subject" required name="subject" placeholder = "Subject" type="text"/>
							
							<label for="message">Message *</label>
							<textarea class="form-control" rows="9" name="message" id="message" placeholder="Message"></textarea>
						</div>
						<hr>
						<div class="form-group btn-group">
							<input class="btn btn-lg btn-success" type="submit" value="Submit"/>
							<input class="btn btn-lg btn-danger" type="reset" value="Reset"/>
						</div>
					</form>					
				</div>
			</div>
		</div>
		
		
						
						
		
		<?php include ('./includes/footer.php');?>		
		<!-- END CONTENT -->
	</body>
</html>