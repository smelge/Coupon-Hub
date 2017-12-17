<?php
	include_once('./includes/security.php');
	// Set up Sessions
	$pageTitle = 'The Coupon Hub';
	$metaKeywords = '';

	if(isset($_COOKIE['CH-userdata'])){
		header('Location: ./index.php');
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
	
	<body class="container-fluid welcomeBg">
		<!-- START CONTENT -->
		<div class="row" style="margin-top:50px;">
			<a href="./index.php?ic=c">
				<div class="col-sm-5 col-sm-offset-1 welcomeBlock">
					Customer Path
				</div>
			</a>
			<div class="col-sm-6"></div>
		</div>
		<div class="row overflow-catch">
			<div class="col-sm-12 tilt-slider">
				<?php include('./includes/offers_slider.php');?>
			</div>
		</div>
		<div class="row" style="margin-bottom:50px;">
			<a href="./index.php?ic=v">
				<div class="col-sm-5 col-sm-offset-6 welcomeBlock">
					Vendor Path
				</div>
			</a>				
		</div>
		<!--
		<a class="btn btn-lg btn-default" href="?ic=c">Customer</a>
		<a class="btn btn-lg btn-default" href="?ic=v">Vendor</a>	
		-->
		<?php include ('./includes/footer.php');?>		
		<!-- END CONTENT -->
	</body>
</html>