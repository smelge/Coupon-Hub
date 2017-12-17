<?php
	//include ('./contact_database.php'); //$dbContact

	// Get variables
	
	if(isset($_POST['userId'])){
		$userId = $_POST['userId'];
		if(isset($_POST['businessId'])){
			$vendorId = $_POST['businessId'];
		}
	}
	
	$message_id = stripslashes(filter_input(INPUT_POST, '', FILTER_SANITIZE_SPECIAL_CHARS));
	
	$user = stripslashes(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS));
	$email = stripslashes(filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS));
	$subject = stripslashes(filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_SPECIAL_CHARS));
	$message = stripslashes(filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS));
	
	// Check if all fields are filled out
	if ($message == ''){
		header('Location: ../help.php?result=7');
	} else {
		$CH = 'couponhub.co.uk@gmail.com';
		$from = 'From: '.$email;
		$messageBody = '
Sent by: '.$user
.' '.$message;
		
		mail($CH,$subject,$from,$messageBody);
	}
	header('Location: ../help.php?result=6');
	
	
	// If filled, process
	
	// Send email to registered email if logged in
	
	// Send email to supplied email if logged out
	
	// Save email to DB
	
	// Send email to The Coupon Hub
	
	// Return to contact page with notification if successful
	
	// Return to contact page with POST variables for form if unsuccessfully sent
	
?>