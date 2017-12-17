<?php
	session_start();	
	include_once('./members_ud.php'); //$mem_update
	date_default_timezone_set("Europe/London");
	$today_date = date("Y-m-d");
	
	$dealId = $_GET['item'];
	$vendorId = $_SESSION['company_id'];
	$type = $_GET['type'];
	
	if ($type == 'FB'){
		// Facebook Post
		$updateQuery = "UPDATE `SNP_deal_links` SET `sharedFacebook` = `sharedFacebook` + 1, `lastShareFacebook` = '$today_date' WHERE `dealId` = '$dealId' AND `partnerId` = '$vendorId'";
	} else {
		// Twitter Post
		$updateQuery = "UPDATE `SNP_deal_links` SET `sharedTwitter` = `sharedTwitter` + 1, `lastShareTwitter` = '$today_date' WHERE `dealId` = '$dealId' AND `partnerId` = '$vendorId'";
	}
	
	if (!mysqli_query($mem_update,$updateQuery)){
		die();// Gone Wrong
		// close window
		echo "<script>window.close();</script>";
	} else {
		// close window	
		echo "<script>window.close();</script>";
	}	
?>