<?php
	include_once ('./members_ud.php');

	$type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS);
	if (!isset($type)){
		$type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_SPECIAL_CHARS);
	}
	$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
	
	$check_email = mysqli_query($mem_update,"SELECT * FROM `members` WHERE `email` = '$email'");
	$check_mail_rows = mysqli_num_rows($check_email);
	
	if ($check_mail_rows != 1){
		header('Location: ../'.$homelink.'?result=18');
	} else {
		$process_setup = mysqli_query($mem_update,"SELECT * FROM `members` WHERE `email` = '$email'");
		$process = mysqli_fetch_array($process_setup);	
		
		switch ($type){
			case 'activate':
			$subject = "The Coupon Hub Registration";
			$headers = "From: Registration@thecouponhub.co.uk";
			
			$content = 
			'Thank you for registering an account with The Coupon Hub. 
			To Activate your account, please follow the following link: http://www.thecouponhub.co.uk/activation.php?user='.$process['username'].'&activation='.$process['verification_code'];
			mail($email,$subject,$content,$headers);			
			header('Location: ../'.$homelink.'?result=3');
			break;
			
			case 'password':
			// Update password with new one
			$Allowed_Characters = 'abcdefghijkmnpqrstuvwxyzBCDEFGHIJKLMNPQRSTUVWXYZ23456789';
			$new_password = substr(str_shuffle($Allowed_Characters), 0, 6);
			$salt = $process['salty'];			
			$encrypted = crypt($new_password,$salt);
			// email new password
			$subject = "The Coupon Hub Lost Password & Usernames";
			$headers = "From: registration@thecouponhub.co.uk";
			
			$content = 
			'Please use the following credentials to log in to your account. You should change your password immediately.
			Username: '.$process['username'].'
			Password: '.$new_password;
			
			mail($email,$subject,$content,$headers);	

			// Send user PM to remind them to change their password
			
			$sender = 12;
			$recipient = $process['user_id'];
			
			$title = 'Password Recovery Notice';
			$message = '
				You have recovered your password.</br></br>
				
				Please change the automated password with a new one as soon as possible.</br>
				This can be done here: <a href="./myaccount.php">User Profile</a>.</br></br>
				
				If you have any issues, either Reply to this message, or email: help@thecouponhub.co.uk
			';
			
			$time = date("Y-m-d H:i:s");
			
			$encode_string = $sender.'-'.$recipient.'-'.$time;
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
					'$sender',
					'$recipient',
					'$time',
					'$title',
					'$message')
			";
			mysqli_query($mem_update,$sqlpath);			
			
			// encode new password for DB
			$sqlpath = "UPDATE `members` SET `password` = '$encrypted' WHERE `email` = '$email'";
			if (!mysqli_query($mem_update,$sqlpath)) {
				die(header('Location: ../forgotten.php?result=5'));// Gone Wrong
			} else {
				header('Location: ../'.$homelink.'?result=6');
			}
			break;
		}
	}
?>