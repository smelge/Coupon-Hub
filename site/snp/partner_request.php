<?php
	// Set up Sessions
	include_once('./includes/snp_security.php');
	//Restricted page, check for eligibility, if not send to index
	if (!isset($_SESSION['company'])){
		header('Location: ../'.$homelink);		
	}

	$vendor_id = $_SESSION['company_id'];
	$vendor_user_id = $_SESSION['user_id'];
	$partner_id = filter_input(INPUT_GET, 'vendor', FILTER_SANITIZE_SPECIAL_CHARS);
	
	$partner_username_find_set = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `vendor_id` = '$partner_id'");
	$partner_username_find = mysqli_fetch_array($partner_username_find_set);
	$partner_username = $partner_username_find['username'];
		
	$get_partner_name_set = mysqli_query($secure_db,"SELECT * FROM `members` WHERE `username` = '$partner_username'");
	$get_partner_name = mysqli_fetch_array($get_partner_name_set);
	$partner_user = $get_partner_name['user_id'];
	
	// Insert into social_network_partners
	$sql_partner = "INSERT INTO `social_network_partners` (`vendor_id`,`partner_id`) VALUES ('$vendor_id','$partner_id')";
	if (!mysqli_query($mem_update,$sql_partner)){
		die(header('Location: ./find_partner.php?error=failure-to-send'));// Gone Wrong
	} else {				
		// Send mail to Partner
				
		$title = $_SESSION['company'].' would like to form a Social Network Partnership with you!';
		$message = '
			Greetings, '.$_SESSION['company'].' believes a Social Network Partnership between your two 
			companies would be beneficial to the both of you.</br></br>
			You can find the full details by clicking this button:</br><span style="text-align:center;">
			<a class="btn btn-success" href="./snp/request.php">View your Partner Requests</a></span>';
		
		$time = date("Y-m-d H:i:s");
		
		$encode_string = $vendor_user_id.'-'.$partner_id.'-'.$time;
		$encoded = md5($encode_string);
		
		$sqlpath = "
			INSERT INTO `private_messages` (
				`ident`,
				`from_id`,
				`to_id`,
				`time_sent`,
				`title`,
				`message`) 
			VALUES (
				'$encoded',
				'$vendor_user_id',
				'$partner_user',
				'$time',
				'$title',
				'$message')
		";
		if (!mysqli_query($mem_update,$sqlpath)) {
			die(header('Location: ../mail.php?m='.$boxtype.'&result=error'));// Gone Wrong
		} else {				
			header('Location: ../mail.php?m=outbox');
		}
	}
?>