<?php
	
	include_once ('./members_ud.php'); //$mem_update
	$homelink = '../index.php';
	
	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_SPECIAL_CHARS);
	$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_SPECIAL_CHARS);
	$password_check = filter_input(INPUT_POST, 'repeatpassword', FILTER_SANITIZE_SPECIAL_CHARS);
	$forename = filter_input(INPUT_POST, 'forename', FILTER_SANITIZE_SPECIAL_CHARS);
	$surname = filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_SPECIAL_CHARS);
	$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_SPECIAL_CHARS);
	$gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_SPECIAL_CHARS);
	$dob_day = filter_input(INPUT_POST, 'day', FILTER_SANITIZE_SPECIAL_CHARS);
	$dob_month = filter_input(INPUT_POST, 'month', FILTER_SANITIZE_SPECIAL_CHARS);
	$dob_year = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_SPECIAL_CHARS);
	$phone = filter_input(INPUT_POST, 'businessPhone', FILTER_SANITIZE_SPECIAL_CHARS);
	$house = filter_input(INPUT_POST, 'address1', FILTER_SANITIZE_SPECIAL_CHARS);
	$street = filter_input(INPUT_POST, 'address2', FILTER_SANITIZE_SPECIAL_CHARS);
	$town = filter_input(INPUT_POST, 'address3', FILTER_SANITIZE_SPECIAL_CHARS);
	$postcode = filter_input(INPUT_POST, 'address4', FILTER_SANITIZE_SPECIAL_CHARS);
	$country = filter_input(INPUT_POST, 'address5', FILTER_SANITIZE_SPECIAL_CHARS);
	$terms = filter_input(INPUT_POST, 'termscheck', FILTER_SANITIZE_SPECIAL_CHARS);
	
	$company = filter_input(INPUT_POST, 'company', FILTER_SANITIZE_SPECIAL_CHARS);
	$company_info = filter_input(INPUT_POST, 'profile', FILTER_SANITIZE_SPECIAL_CHARS);
	$facebook = filter_input(INPUT_POST, 'facebook', FILTER_SANITIZE_SPECIAL_CHARS);
	$facebook = str_ireplace("https://www.facebook.com/","",$facebook);
	$website = filter_input(INPUT_POST, 'website', FILTER_SANITIZE_SPECIAL_CHARS);
	
	$website_correct = explode("www.",$website);
	if ($website_correct[0] == false){
		$website = $website_correct[1];
	}

	$sector = filter_input(INPUT_POST, 'sector', FILTER_SANITIZE_SPECIAL_CHARS);
	
	$usertype = 2;

	$salt = '$5$'.md5(time());
	$verification = md5(time());
	$encrypted = crypt($password,$salt);
	$check = crypt($password_check,$salt);
	
	$dob = $dob_year.'-'.$dob_month.'-'.$dob_day;
	
	//check if username is already in DB
	$username_check_set = mysqli_query($mem_update,"SELECT * FROM `members` WHERE `username` = '$username'");
	$username_check = mysqli_num_rows($username_check_set);
	if ($username_check == true){
		header('Location: ../register.php?type=user&result=16');
	}	
	//check if password == password_check
	if ($password != $password_check){
		header('Location: ../register.php?type=user&result=17');
	}
	//do stuff
	
	$sqlpath = "
		INSERT INTO `members` (
			`username`,
			`usertype`,
			`email`,
			`password`,
			`salty`,
			`forename`,
			`surname`,
			`gender`,
			`date_of_birth`,
			`house`,
			`street`,
			`town`,
			`postcode`,
			`country`,
			`verification_code`,
			`company`) 
		VALUES (
			'$username',
			'$usertype',
			'$email',
			'$encrypted',
			'$salt',
			'$forename',
			'$surname',
			'$gender',
			'$dob',
			'$house',
			'$street',
			'$town',
			'$postcode',
			'$country',
			'$verification',
			'$company')
	";
	if (!mysqli_query($mem_update,$sqlpath)) {
		die(header('Location: ../'.$homelink.'?result=5'));// Gone Wrong
	} else {
		$sqlpath = "
			INSERT INTO `vendors` (
				`company`,
				`username`,
				`company_profile`,
				`facebook`,
				`email`,
				`website`,
				`sector`,
				`telephone`,
				`house`,
				`street`,
				`town`,
				`postcode`,
				`country`) 
			VALUES (
				'$company',
				'$username',
				'$company_info',
				'$facebook',
				'$email',
				'$website',
				'$sector',
				'$phone',
				'$house',
				'$street',
				'$town',
				'$postcode',
				'$country')
		";
		if (!mysqli_query($mem_update,$sqlpath)) {
			die(header('Location: ../'.$homelink.'?result=5'));// Gone Wrong
		} else {
			// Send email with verification code & link
			$username_encoded = md5($username);
			
			$subject = "The Coupon Hub Company Registration";
			$headers = "From: registration@thecouponhub.co.uk";
			
			$content = 
'Thank you for registering '.$company.' with The Coupon Hub. 
To Activate your account, please follow the following link: http://www.thecouponhub.co.uk/activation.php?user='.$username_encoded.'&activation='.$verification;
				
			mail($email,$subject,$content,$headers);			
			header('Location: ../'.$homelink.'?result=3');
		}
	}
?>