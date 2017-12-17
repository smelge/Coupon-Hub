<?php
	include_once('./evms_basic.php');
	$voucher_code = filter_input(INPUT_POST, 'voucher', FILTER_SANITIZE_SPECIAL_CHARS);
	$vendor_id = filter_input(INPUT_POST, 'vendor_id', FILTER_SANITIZE_SPECIAL_CHARS);

	//Check database, if voucher exists & no customer ID or status is not 3 = coupon exists and unclaimed, get deal_id</br>
	//Use Deal_id for vendor ID match, check coupon is within dates.</br>
	//If all accepted, set coupon status to 3(redeemed offline), else give relevant error message
	
	//$evms_db
	
	$evms_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_repo` WHERE `serial` = '$voucher_code'");
	if(mysqli_num_rows($evms_setup) != 1){
		// Coupon does not exist, or has multiple entries
		header('Location: ../evms_validate.php?result=existencefailure');
	} else {
		// coupon exists, check status
		// 0 = unused, 1 = downloaded, 2 = in basket, 3 = redeemed
		$evms = mysqli_fetch_array($evms_setup);
		if ($evms['status'] == 0){
			// Coupon is available, has not been downloaded
			header('Location: ../evms_validate.php?result=notdownloaded');
		} elseif ($evms['status'] == 1){
			//Coupon has been downloaded for offline use
			//check dates before validating
			$deal_id = $evms['deal_id'];
			$deal_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `deal_id` = '$deal_id'");
			$deal = mysqli_fetch_array($deal_setup);
			$date = date("Y-m-d");
			if ($date >= $deal['start_date'] && $date <= $deal['end_date']){
				//Coupon in date
				//Update coupon status to 3
				$sqlipath = "
					UPDATE `coupon_repo`
					SET
						`status` = '3',
						`customer_id` = '$vendor_id'
					WHERE
						`serial` = '$voucher_code'
				";
				if (!mysqli_query($evms_db,$sqlipath)){
					die(header('Location: ../evms_validate.php?result=panic'));// Gone Wrong
				} else {
					//Pass user back to validation with deal_id
					header('Location: ../evms_validate.php?result=success&id='.$evms['deal_id']);
				}
			} else {
				if ($date < $deal['start_date']){
					//Offer still to start
					header('Location: ../evms_validate.php?result=tooearly&id='.$evms['deal_id']);
				} elseif ($date > $deal['end_date']){
					//Offer ended
					header('Location: ../evms_validate.php?result=toolate&id='.$evms['deal_id']);
				}
			}
			
		} elseif ($evms['status'] == 2){
			//Coupon is in a users basket
			header('Location: ../evms_validate.php?result=notdownloaded');
		} elseif ($evms['status'] == 3){
			//Coupon has already been redeemed
			header('Location: ../evms_validate.php?result=irredeemable');
		}
		
	}
?>