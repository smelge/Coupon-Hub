<?php
	session_start();
	include_once('../../includes/members_ud.php');
	
	$snp_id = filter_input(INPUT_GET, 'd', FILTER_SANITIZE_SPECIAL_CHARS);
	$result = filter_input(INPUT_GET, 'p', FILTER_SANITIZE_SPECIAL_CHARS);
	
	$vendor_user_id = $_SESSION['user_id'];
	
	$find_partner_vendor_id = mysqli_query($mem_update,"SELECT * FROM `social_network_partners` WHERE `id` = '$snp_id'");
	$partner_vendor_id = mysqli_fetch_array($find_partner_vendor_id);
	$partner_vendor_id = $partner_vendor_id['vendor_id'];
	
	$partner_id_locate = mysqli_query($mem_update,"SELECT `members`.`user_id` FROM `members` LEFT JOIN `vendors` ON `members`.`username` = `vendors`.`username` WHERE `vendors`.`vendor_id` = '$partner_vendor_id'");
	$partner_id = mysqli_fetch_array($partner_id_locate);
	$partner_id = $partner_id['user_id'];
	
	switch($result){
		case 0:
			//Decline SNP
			if (!mysqli_query($mem_update,"UPDATE `social_network_partners` SET `approved` = 2 WHERE `id` = '$snp_id'")) {
				die(header('Location: ../vendor_profile.php&result=error'));// Gone Wrong
			} else {
				$title = $_SESSION['company'].' has <b>declined</b> a Social Network Partnership with you.';
				$message = 'Sorry, but '.$_SESSION['company'].' does not agree that a Social Network Partnership between your two companies would be beneficial to the both of you.';
					
				$time = date("Y-m-d H:i:s");
					
				$encode_string = $vendor_user_id.'-'.$partner_id.'-'.$time;
				$encoded = md5($encode_string);
					
				$PMpath = "
					INSERT INTO `private_messages` (
						`ident`,
						`from_id`,
						`to_id`,
						`time_sent`,
						`title`,
						`message`) 
					VALUES (
						'$encoded',
						'$partner_id',
						'$vendor_user_id',
						'$time',
						'$title',
						'$message')
				";
				
				mysqli_query($mem_update,$PMpath);
				header('Location: ../vendor_profile.php');
			}
			break;
		case 1:
			//Accept SNP
			if (!mysqli_query($mem_update,"UPDATE `social_network_partners` SET `approved` = 1 WHERE `id` = '$snp_id'")) {
				die(header('Location: ../vendor_profile.php&result=error'));// Gone Wrong
			} else {
				$title = $_SESSION['company'].' has <b>Accepted</b> a Social Network Partnership with you.';
				$message = 'Congratulations, '.$_SESSION['company'].' agrees that a Social Network Partnership between your two companies would be beneficial to the both of you.</br></br>
						<span style="text-align:center;"><a class="btn btn-success" href="../snp/partnerships.php">View your Partnerships</a></span>';
					
				$time = date("Y-m-d H:i:s");
					
				$encode_string = $vendor_user_id.'-'.$partner_id.'-'.$time;
				$encoded = md5($encode_string);
				
				$PMpath = "
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
						'$partner_id',
						'$time',
						'$title',
						'$message')
				";
				
				mysqli_query($mem_update,$PMpath);
				header('Location: ../partnerships.php');
			}
			break;
	}
?>