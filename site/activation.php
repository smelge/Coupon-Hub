<?php
	if (isset($_SESSION['username'])){
		session_unset();
		session_destroy();
	}
	include_once('./includes/security.php');
	$pageTitle = 'The Coupon Hub Activation';
	$metaKeywords = '';
?>

<!DOCTYPE html>
<html lang="en">
	<head>		
		<?php
			include_once('./includes/meta.php');			
			include_once('./includes/scripts.php');	
			
			$user = filter_input(INPUT_GET, 'user', FILTER_SANITIZE_SPECIAL_CHARS);
			$activation = filter_input(INPUT_GET, 'activation', FILTER_SANITIZE_SPECIAL_CHARS);
			
			$activate_setup = mysqli_query($mem_update,"SELECT * FROM `members` WHERE `verification_code` = '$activation'");
			$activate = mysqli_fetch_array($activate_setup);
		?>
	</head>
	<body class="container-fluid customer-body">
		<!-- START CONTENT -->
		<?php include ('./includes/ch-header.php');?>		
		<div class="row toprow">			
			<div class="col-sm-12 standard">					
				<div class="col-sm-12 heading">
					The Coupon Hub - Activate your account
				</div>
				<div class="col-sm-12" style="text-align:center;font-weight:bold;">
					<?php
						if ($activate['verified'] == 1){
							echo 'You have already activated your account!';
						} else {
							if (md5($activate['username']) == $user){
								echo 'Your account has been activated, you can now Log In.';
								// Update account to verified
								$sqlpath = "UPDATE `members` SET `verified` = '1' WHERE `verification_code` = '$activation'";
								if (!mysqli_query($mem_update,$sqlpath)) {
									die('The Database is not connecting. Please try Activating later.');// Gone Wrong
								}
							} else {
								echo 'Sorry, the Activation codes do not match.';
							}
						}
					?>
				</div>
			</div>
		</div>
		<?php include ('./includes/footer.php');?>		
		<!-- END CONTENT -->
	</body>
</html>