<?php
	session_start();
	date_default_timezone_set("Europe/London");
	$today_date = date("Y-m-d");
	include_once('../../includes/members_ud.php'); //$mem_update
	
	$deal_id = $_GET['d'];
	$vendorId = $_SESSION['company_id'];
	
	$checkDeal_set = mysqli_query($mem_update,"SELECT * FROM `SNP_deal_links` WHERE `id` = '$deal_id' AND `partnerId` = '$vendorId'");
	if (mysqli_num_rows($checkDeal_set) == 0){
		header('Location: ../deal_request.php?error=No-Deal-Found');
	} else {
		if($_GET['a'] == 'yes'){
			if (!mysqli_query($mem_update,"UPDATE `SNP_deal_links` SET `agreed` = 1 ,`dateActivated` = '$today_date' WHERE `id` = '$deal_id'")) {
				die(header('Location: ../deal_request.php&result=error'));// Gone Wrong
			}
			header('Location: ../../review_deals.php');
		} elseif ($_GET['a']= 'no'){
			if (!mysqli_query($mem_update,"UPDATE `SNP_deal_links` SET `agreed` = 2 ,`dateActivated` = '$today_date' WHERE `id` = '$deal_id'")) {
				die(header('Location: ../myaccount.php&result=error'));// Gone Wrong
			}
			header('Location: ../../myaccount.php');
		} else {
			header('Location: ../deal_request.php?error=Unknown-thingy');
		}
	}
?>