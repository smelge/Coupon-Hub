<?php
	// Set up Sessions
	include_once('./includes/security.php');
	//Restricted page, check for eligibility, if not send to index
	if (!isset($_SESSION['company'])){
		header('Location: ./'.$homelink);		
	}
	$pageTitle = 'The Coupon Hub';
	$metaKeywords = '';
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php
			include_once('./includes/meta.php');			
			include_once('./includes/scripts.php');				
		
			$company_name = $_SESSION['company'];
			$company_set = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `company` = '$company_name'");
			$company = mysqli_fetch_array($company_set);
			$vendor_id = $company['vendor_id'];
			
			$coupon_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
			
			if ($coupon_id == true){
				$coupon_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `deal_id` = '$coupon_id'");
				$coupon = mysqli_fetch_array($coupon_setup);
			}
		?>
	</head>
	<body class="container-fluid vendor-body">
		<!-- START CONTENT -->
		<?php 
			include ('./includes/ch-vendor-header.php');
			include ('./includes/banner.php');
			
			$couponResult = $_GET['result'];
			
			if (isset($couponResult)){
				// Display relevant alert
				
				switch ($couponResult){
					case 'existencefailure':
						$alertContent = 'Sorry, this coupon code does not exist.</br>Please check you entered the code correctly. Remember the codes are case-sensitive.';
						$alertType = 'danger';
						break;
					case 'notdownloaded':
						$alertContent = 'Sorry, this coupon has not been downloaded, so the customer should not have this code.</br>It cannot be redeemed.';
						$alertType = 'danger';
						break;
					case 'panic':
						$alertContent = 'Sorry, the database is having issues. Please try again in a minute.';
						$alertType = 'danger';
						break;
					case 'success':
						$alertContent = '
							<b>SUCCESS!</b></br>This coupon code is valid, and has been marked as Redeemed in your control panel.</br></br>
							Offer: '.$coupon['title'].'</br>
							Discount: '.$coupon['percent'].'%</br>
							Discounted Price: &pound;'.$coupon['discount'].'</br>
							From: '.dateEdit($coupon['start_date']).'</br>To: '.dateEdit($coupon['end_date'])
						;
						$alertType = 'success';
						break;
					case 'tooearly':
						$alertContent = 'This coupon is valid, but the offer has not yet started. It is valid from</br>'.$coupon['start_date'].' until '.$coupon['end_date'];
						$alertType = 'warning';
						break;
					case 'toolate':
						$alertContent = 'Sorry, this coupon code is out of date. It finished on '.$coupon['end_date'];
						$alertType = 'warning';
						break;
					case 'irredeemable':
						$alertContent = 'Sorry, this coupon code has already been redeemed and cannot be used again.';
						$alertType = 'danger';
						break;
				}				
				echo '
					<div class="row topmargin">
						<div class="alert alert-'.$alertType.'" role="alert">'.$alertContent.'</div>
					</div>					
				';
			}
		?>
		<form class="form-horizontal" action="./evms/evms_query.php" method="POST">
			<div class="row topmargin">		
				<div class="col-sm-12 standard pad">
					<div class="col-sm-12">
						<div class="form-heading">
							Validate a Voucher
						</div>
						<div class="form-group">
							<label for="voucher">Voucher Code (Case-Sensitive)</label>
							<input class="form-control" id="voucher" required name="voucher" type="text"/>
							<input type="hidden" name="vendor_id" value="<?php echo $_SESSION['company_id'];?>"/>
						</div>
					</div>
				</div>
			</div>
			<div class="row topmargin">
				<div class="col-sm-12 standard" style="text-align:center;">
					<input class="btn btn-success" id="submit_button" style="margin:0 30px 0 0;" type="submit" value="Check Coupon"/>
					<input class="btn btn-danger" type="reset" value="Reset Form"/>
				</div>
			</div>
		</form>
		<?php include ('./includes/footer.php');?>		
		<!-- END CONTENT -->
	</body>
</html>