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
			<div class="col-sm-12 welcomebar">
				Welcome to</br>
				<img src="./assets/couponhub-banner.png" alt="The Coupon Hub Banner" class="img-responsive center-block"/>
			</div>
		</div>
		<?php include ('./includes/'.$indexContent.'.php');?>		
		<?php include ('./includes/footer.php');?>		
		<!-- END CONTENT -->
	</body>
</html>