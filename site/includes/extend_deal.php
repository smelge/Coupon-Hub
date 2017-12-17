<?php
	// Sessions, date & database connections
	session_start();
	date_default_timezone_set("Europe/London");
	include_once('../evms/evms_basic.php');
	
	// Get POST data
	$deal_id = filter_input(INPUT_POST, 'deal_id', FILTER_SANITIZE_SPECIAL_CHARS);
	$extend_date = $_POST['extend-date'];
	$extend_date = date("Y-m-d", strtotime($extend_date));
	
	//echo $extend_date;
	
	
	// set Extension Date as date from POST
	$extendDateQuery = "UPDATE `coupon_details` SET `end_date` = '$extend_date' WHERE `deal_id` = '$deal_id'";	
	
	// Return to Active coupons
	if (!mysqli_query($evms_db,$extendDateQuery)){
		die(header('Location: ../active_coupons.php?result=Could-Not-Extend-Date'));// Gone Wrong
	} else {				
		header('Location: ../active_coupons.php');
	}
	
?>