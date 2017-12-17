<?php
	// Start session and initiate database links
	session_start();
	include('../../evms/evms_basic.php'); // $evms_db
	include('../../includes/members_link.php'); // $secure_db
	
	// Get all variables
	$offerID = filter_input(INPUT_POST, 'offer', FILTER_SANITIZE_SPECIAL_CHARS);
	$vendorID = filter_input(INPUT_POST, 'vendor', FILTER_SANITIZE_SPECIAL_CHARS);
	$expiryDate = filter_input(INPUT_POST, 'expiry', FILTER_SANITIZE_SPECIAL_CHARS);
	$partners = filter_input(INPUT_POST, 'partnerNo', FILTER_SANITIZE_SPECIAL_CHARS);
	
	// Add entry for deal in Social Network Deals
	$snpDealQuery = "INSERT INTO `social_network_deals` (`vendor_id`,`item_id`,`expiry`) VALUES ('$vendorID','$offerID','$expiryDate')";
	if (!mysqli_query($secure_db,$snpDealQuery)) {
		die(mysqli_error($secure_db));// Gone Wrong
	}
	$dealID = mysqli_insert_id($secure_db);	
	
	// Loop entries for each partner selected
	for ($loop = 1;$loop <= $partners;$loop++){		
		$partner = filter_input(INPUT_POST, 'partner'.$loop, FILTER_SANITIZE_SPECIAL_CHARS);
		
		$partnerQuery = "INSERT INTO `SNP_deal_links` (`dealId`,`partnerId`) VALUES ('$dealID','$partner')";
		if (!mysqli_query($secure_db,$partnerQuery)) {
			die(mysqli_error($secure_db));// Gone Wrong
		}
	}
	
	// Return user to myaccount
	
	header('Location: ../../myaccount.php');
?>