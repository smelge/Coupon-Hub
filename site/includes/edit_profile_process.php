<?php
	session_start();
	include_once('./members_link.php'); //$secure_db
	include_once('./members_ud.php'); //$mem_update
	
	$user_id = $_SESSION['user_id'];
	$vendor_id = $_SESSION['company_id'];
	
	// General variables
	
	//$avatar = ($_FILES['avatar'] ['name']);
			// Check dimensions and size
	
	$user_email = filter_input(INPUT_POST, 'emailPersonal', FILTER_SANITIZE_SPECIAL_CHARS);	
	$old_password = filter_input(INPUT_POST, 'oldPassword', FILTER_SANITIZE_SPECIAL_CHARS);
	$new_password = filter_input(INPUT_POST, 'newPassword', FILTER_SANITIZE_SPECIAL_CHARS);
	$repeat_password = filter_input(INPUT_POST, 'repeatPassword', FILTER_SANITIZE_SPECIAL_CHARS);
	$forename = filter_input(INPUT_POST, 'forename', FILTER_SANITIZE_SPECIAL_CHARS);
	$surname = filter_input(INPUT_POST, 'surname', FILTER_SANITIZE_SPECIAL_CHARS);
	
	$gender = filter_input(INPUT_POST, 'gender', FILTER_SANITIZE_SPECIAL_CHARS);
	$day_ob = filter_input(INPUT_POST, 'day', FILTER_SANITIZE_SPECIAL_CHARS);
	$month_ob = filter_input(INPUT_POST, 'month', FILTER_SANITIZE_SPECIAL_CHARS);
	$year_ob = filter_input(INPUT_POST, 'year', FILTER_SANITIZE_SPECIAL_CHARS);
	$dob = $year_ob.'-'.$month_ob.'-'.$day_ob;
	$user_house = filter_input(INPUT_POST, 'housePersonal', FILTER_SANITIZE_SPECIAL_CHARS);
	$user_street = filter_input(INPUT_POST, 'streetPersonal', FILTER_SANITIZE_SPECIAL_CHARS);
	$user_town = filter_input(INPUT_POST, 'townPersonal', FILTER_SANITIZE_SPECIAL_CHARS);
	$user_postcode = filter_input(INPUT_POST, 'postcodePersonal', FILTER_SANITIZE_SPECIAL_CHARS);
	$user_country = filter_input(INPUT_POST, 'countryPersonal', FILTER_SANITIZE_SPECIAL_CHARS);
	
	if($_GET['type'] == 'vendor'){
		// Vendor Profile Update
		
		$profile = filter_input(INPUT_POST, 'companyProfile', FILTER_SANITIZE_SPECIAL_CHARS);
		$newbanner = ($_FILES['banner-image'] ['name']);
			if(isset($newbanner) && $newbanner == true){
				// Check dimensions and size
				
				$datestring = date_create();
				$datestring = date_timestamp_get($datestring);
				$currentupload = ($_FILES['banner-image'] ['name']);				
				$image_root = $datestring.'-'.$currentupload;
				move_uploaded_file($_FILES['banner-image'] ['tmp_name'], "../assets/vendor-banners/".$image_root);
				//insert into db
				$bannerpath = "UPDATE `vendors` SET `banner` = '$image_root' WHERE `vendor_id` = '$vendor_id'";
				if (!mysqli_query($mem_update,$bannerpath)) {
					die(header('Location: ../edit_profile.php?id='.$_SESSION['user_id'].'&t=v'));// Gone Wrong
				}
			}		
		
		$sector = filter_input(INPUT_POST, 'sector', FILTER_SANITIZE_SPECIAL_CHARS);
		// Opening hours?
		$facebook = filter_input(INPUT_POST, 'facebook', FILTER_SANITIZE_SPECIAL_CHARS);
		
		//echo $facebook;
		if (strpos($facebook,'-') !== false){			
			$facebookLongform = explode("-",$facebook);
			$facebook = $facebookLongform[1];
		}
		
		$linkedin = filter_input(INPUT_POST, 'linkedin', FILTER_SANITIZE_SPECIAL_CHARS);
		$twitter = filter_input(INPUT_POST, 'twitter', FILTER_SANITIZE_SPECIAL_CHARS);
		$business_email = filter_input(INPUT_POST, 'emailBusiness', FILTER_SANITIZE_SPECIAL_CHARS);
		$website = filter_input(INPUT_POST, 'website', FILTER_SANITIZE_SPECIAL_CHARS);
		$display_address = filter_input(INPUT_POST, 'showAddress', FILTER_SANITIZE_SPECIAL_CHARS);
		$bus_phone = filter_input(INPUT_POST, 'phoneBusiness', FILTER_SANITIZE_SPECIAL_CHARS);
		$bus_house = filter_input(INPUT_POST, 'houseBusiness', FILTER_SANITIZE_SPECIAL_CHARS);
		$bus_street = filter_input(INPUT_POST, 'streetBusiness', FILTER_SANITIZE_SPECIAL_CHARS);
		$bus_town = filter_input(INPUT_POST, 'townBusiness', FILTER_SANITIZE_SPECIAL_CHARS);
		$bus_postcode = filter_input(INPUT_POST, 'postcodeBusiness', FILTER_SANITIZE_SPECIAL_CHARS);
		$bus_country = filter_input(INPUT_POST, 'countryBusiness', FILTER_SANITIZE_SPECIAL_CHARS);
		
		$json_url ='https://graph.facebook.com/'.$facebook.'?access_token=1703713406526137|1bd51b8e55d973d8e0596a9dbc351bd8&fields=likes';		
		if (@file_get_contents($json_url) === false){
			// Incorrect facebook link, update everything but Facebook
			$sendTo = 0;
			$vendorpath = "
				UPDATE `vendors` SET
					`company_profile` = '$profile',
					`linkedin` = '$linkedin',
					`email` = '$business_email',
					`twitter` = '$twitter',
					`website` = '$website',
					`sector` = '$sector',
					`telephone` = '$bus_phone',
					`house` = '$bus_house',
					`street` = '$bus_street',
					`town` = '$bus_town',
					`postcode` = '$bus_postcode',
					`country` = '$bus_country',
					`show_address` = '$display_address'
				WHERE
					`vendor_id` = '$vendor_id'
			";	
		} else {
			// Correct Facebook, normal update
			$sendTo = 1;
			$vendorpath = "
				UPDATE `vendors` SET
					`company_profile` = '$profile',
					`facebook` = '$facebook',
					`linkedin` = '$linkedin',
					`email` = '$business_email',
					`twitter` = '$twitter',
					`website` = '$website',
					`sector` = '$sector',
					`telephone` = '$bus_phone',
					`house` = '$bus_house',
					`street` = '$bus_street',
					`town` = '$bus_town',
					`postcode` = '$bus_postcode',
					`country` = '$bus_country',
					`show_address` = '$display_address'
				WHERE
					`vendor_id` = '$vendor_id'
			";	
		}
		
		
		if (!mysqli_query($mem_update,$vendorpath)) {
			die(header('Location: ../edit_profile.php?id='.$vendor_id.'&t=v&error=Update_failure'));// Gone Wrong
		}
		
		$userpath = "
			UPDATE `members` SET
				`email` = '$user_email',
				`forename` = '$forename',
				`surname` = '$surname',
				`gender` = '$gender',
				`date_of_birth` = '$dob',
				`house` = '$user_house',
				`street` = '$user_street',
				`town` = '$user_town',
				`postcode` = '$user_postcode',
				`country` = '$user_country'
			WHERE
				`user_id` = '$user_id'
		";	
		if (!mysqli_query($mem_update,$userpath)) {
			die(header('Location: ../edit_profile.php?id='.$vendor_id.'&t=v&error=Update_failure'));// Gone Wrong
		}
		
		switch ($sendTo){
			case 0:
				// Fail
				header('Location: ../edit_profile.php?id='.$vendor_id.'&t=v?error=Incorrect+Facebook');
				break;
			case 1:
				// Success
				header('Location: ../edit_profile.php?id='.$vendor_id.'&t=v');
				break;
			default:
				// wtf
				header('Location: ../edit_profile.php?id='.$vendor_id.'&t=v?error=Unknown+error');
				break;		
		}		
	} elseif ($_GET['type'] == 'user'){
		// User Profile Update
		$userpath = "
			UPDATE `members` SET
				`email` = '$user_email',
				`forename` = '$forename',
				`surname` = '$surname',
				`gender` = '$gender',
				`date_of_birth` = '$dob',
				`house` = '$user_house',
				`street` = '$user_street',
				`town` = '$user_town',
				`postcode` = '$user_postcode',
				`country` = '$users_country'
			WHERE
				`user_id` = '$user_id'
		";	
		if (!mysqli_query($mem_update,$userpath)) {
			die(header('Location: ../edit_profile.php?id='.$user_id.'&t=v&error=Update_failure'));// Gone Wrong
		} else {
			header('Location: ../edit_profile.php?id='.$user_id.'&t=u');
			
		}
	}
?>