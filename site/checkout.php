<?php
	// Set up Sessions
	include_once('./includes/security.php');
	
	$pageTitle = 'The Coupon Hub';
	$metaKeywords = '';

	// If not logged in, return to index
	if (!isset($_SESSION['username'])){
		header('Location: ./'.$homelink);
	} else {
		$userID = $_SESSION['user_id'];
		$redeemDate = date("Y-m-d H:i:s");
	}
	
	// Get all items from users baskets
	$findBaskets = mysqli_query($db_basket,"SELECT * FROM `shopping_basket` WHERE `user_id` = '$userID'");
	
	// Conglomerate Items into single basket
	$itemArray = '';
	$couponArray = '';
	while ($userBaskets = mysqli_fetch_array($findBaskets)){
		$itemArray .= $userBaskets['items'];
		$couponArray .= $userBaskets['coupons'];
	}	
	
	// Add conglomerated basket to Redeemed Baskets
	
	$redeemedQuery = "INSERT INTO `redeemed_baskets`(`user_id`,`date_redeemed`,`items`,`coupons`) VALUES ('$userID','$redeemDate','$itemArray','$couponArray')";
	if (!mysqli_query($db_basket,$redeemedQuery)){
		echo (mysqli_error());
	}
	
	// Set each coupon in basket to Status 1 in coupon_repo
	$splitCoupons = explode(";",$couponArray);
	
	foreach ($splitCoupons as $updateCoupon){
		if (!mysqli_query($evms_db,"UPDATE `coupon_repo` SET `status` = '1' WHERE `coupon_id` = '$updateCoupon'")){
			die(mysqli_error());
		}
	}
	
	// Delete original baskets from shopping_baskets
	$setRemoveBaskets = mysqli_query($db_basket,"SELECT * FROM `shopping_basket` WHERE `user_id` = '$userID'");
	while ($removeBasket = mysqli_fetch_array($setRemoveBaskets)){
		$currBasket = $removeBasket['basket_id'];
		if (!mysqli_query($db_basket,"DELETE FROM `shopping_basket` WHERE `basket_id` = '$currBasket'")){
			die(mysqli_error());
		}
	}	
	
	// Create and send email with coupon codes
	$findUserEmail = mysqli_query($secure_db,"SELECT * FROM `members` WHERE `user_id` = '$userID'");
	$getUserEmail = mysqli_fetch_array($findUserEmail);
	
	$userEmail = $getUserEmail['email'];
	$subject = "Your Coupon Hub Codes";
	$headers = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= 'From: <coupons@thecouponhub.co.uk>' . "\r\n";
	
	$body = "<html><head><title>Your Coupon Hub Coupons</title></head><body>";
	
	$body .= "Here are the Coupon codes you have selected on www.thecouponhub.co.uk<hr>";
	foreach ($splitCoupons as $coupon){
		if ($coupon != false){
			// Get serial Number, Deal ID
			$getCouponDetails = mysqli_query($evms_db,"SELECT * FROM `coupon_repo` WHERE `coupon_id` = '$coupon'");
			$couponDetails = mysqli_fetch_array($getCouponDetails);
			
			$couponSerial = $couponDetails['serial'];
			$dealID = $couponDetails['deal_id'];
			
			// Use Deal ID to get coupon title, vendor ID, discount & percentage
			$getDeal = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `deal_id` = '$dealID'");
			$deal = mysqli_fetch_array($getDeal);
			
			$couponTitle = $deal['title'];
			$couponDiscount = $deal['discount'];
			$couponPercent = $deal['percent'];
			$couponVendor = $deal['vendor_id'];
			
			// Use Vendor ID to get Vendor Name, Address
			$getVendor = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `vendor_id` = '$couponVendor'");
			$vendor = mysqli_fetch_array($getVendor);
			
			$vendorName = $vendor['company'];
			$vendorAddress = $vendor['house'].'<br>';
			$vendorAddress .= $vendor['street'].'<br>';
			$vendorAddress .= $vendor['town'].'<br>';
			$vendorAddress .= $vendor['postcode'].'<br>';
			$vendorAddress .= $vendor['country'];
			
			$body .= $couponTitle.' - &#163;'.$couponDiscount.' ('.$couponPercent.'% off)<br>';
			$body .= 'Voucher Code: '.$couponSerial.'<br>';
			$body .= 'Sold by '.$vendorName.'<br>';
			$body .= $vendorAddress.'<hr>';			
		}
	}
	$body .= "You can also find the details and codes on you User Profile on www.thecouponhub.co.uk<br><br>All coupons are subject to time limitations, so please check and redeem them before they expire.<br><br>Thanks,<br> from The Coupon Hub";
	$body .= "</body></html>";
	
	$body = wordwrap($body,70);
	
	mail($userEmail,$subject,$body,$headers);
	
	// Return user to basket.php
	header('Location: ./basket.php');
?>