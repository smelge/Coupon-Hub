<?php
	session_start();
	date_default_timezone_set("Europe/London");
	include_once('./members_ud.php');
	include_once('./shop_connect.php');
	include_once('./basket_database.php');
	include_once('../evms/evms_basic.php');
	
	$item = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
	
	$get_coupon_details = mysqli_query($evms_db,"SELECT * FROM `coupon_repo` WHERE `deal_id` = '$item' AND `status` = 0");
	$number = mysqli_num_rows($get_coupon_details);
	//echo 'Available coupons: '.$number;
	
	if (mysqli_num_rows($get_coupon_details) == 0){
		//header('Location:../shop.php?error=out-of-stock');
	}
	$coupon_details = mysqli_fetch_array($get_coupon_details);	
	$coupon = $coupon_details['coupon_id'];
	$coupon_ud = $coupon.';';	
	$item_ud = $item.';';
	$timestamp = date("Y-m-d H:i:s");	
	
	if (isset($_SESSION['user_id'])){
		$user = $_SESSION['user_id'];
		$user_query = "
			INSERT INTO `shopping_basket` 
			(`user_id`,`items`,`coupons`,`date_created`) 
			VALUES 
			('$user','$item_ud','$coupon_ud','$timestamp')
		";
		$set_basket = mysqli_query($db_basket,"SELECT * FROM `shopping_basket` WHERE `user_id` = '$user' AND `date_redeemed` = '0000-00-00 00:00:00'");
	} else {
		$user = $_SERVER['REMOTE_ADDR'];
		$user_query = "
			INSERT INTO `shopping_basket` 
			(`logged_out_user`,`items`,`coupons`,`date_created`) 
			VALUES 
			('$user','$item_ud','$coupon_ud','$timestamp')
		";
		$set_basket = mysqli_query($db_basket,"SELECT * FROM `shopping_basket` WHERE `logged_out_user` = '$user' AND `date_redeemed` = '0000-00-00 00:00:00'");
	}	
		
	$find_basket = mysqli_num_rows($set_basket);
	if ($find_basket == 0){
		$sql_query_basket = $user_query;
		$sql_query_coupons = "UPDATE `coupon_repo` SET `status` = '2', `customer_id` = '$user' WHERE `coupon_id` = '$coupon' AND `deal_id` = '$item'";
	} else {
		$current_basket = mysqli_fetch_array($set_basket);
		$basket_id = $current_basket['basket_id'];
		$current_items = $current_basket['items'].$item_ud;
		$current_coupons = $current_basket['coupons'].$coupon_ud;
		if (isset($_SESSION['user_id'])){
			$sql_query_basket = "UPDATE `shopping_basket` SET `items` = '$current_items', `coupons` = '$current_coupons' WHERE `basket_id` = '$basket_id' AND `user_id` = '$user'";
		} else {
			$sql_query_basket = "UPDATE `shopping_basket` SET `items` = '$current_items', `coupons` = '$current_coupons' WHERE `basket_id` = '$basket_id' AND `logged_out_user` = '$user'";

		}
		$sql_query_coupons = "UPDATE `coupon_repo` SET `status` = '2', `customer_id` = '$user' WHERE `coupon_id` = '$coupon' AND `deal_id` = '$item'";
	}
	
	if (!mysqli_query($db_basket,$sql_query_basket) || !mysqli_query($evms_db,$sql_query_coupons)) {
		die(header('Location: ../shop.php?item='.$item.'&result=error'));// Gone Wrong
	} else {				
		header('Location: ../shop.php?item='.$item);
	}
?>