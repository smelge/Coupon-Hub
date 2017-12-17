<?php
	session_start();
	date_default_timezone_set("Europe/London");
	$today_date = date("Y-m-d");
	
	$homelink = 'index.php';
	include_once('../includes/category_connect.php');
	include_once('../includes/members_link.php');
	include_once('../evms/evms_basic.php');
	include_once('../includes/shop_connect.php');
	include_once ('../includes/basket_database.php');
	include_once('../includes/members_ud.php');
	
	$product_number = 0;
	
	if (!isset($_SESSION['usertype'])){
		$_SESSION['usertype'] = 0;
	}
	
	if(isset($_SESSION['user_id'])){
		$user_id = $_SESSION['user_id'];
		$get_basket = mysqli_query($db_basket,"SELECT * FROM `shopping_basket` WHERE `user_id` = '$user_id' AND `date_redeemed` = '0000-00-00 00:00:00'");
		
		while ($basket = mysqli_fetch_array($get_basket)){
			$product = explode(";",$basket['items']);		
			foreach ($product as $findProducts){
				$productSetup = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `deal_id` = '$findProducts'");
				$productCount = mysqli_fetch_array($productSetup);
				if ($productCount['start_date'] < $today_date && $productCount['end_date'] > $today_date){
					$product_number++;
				}
			}
		}
	} else {
		$user_id = $_SERVER['REMOTE_ADDR'];
		$get_baskets = mysqli_query($db_basket,"SELECT * FROM `shopping_basket` WHERE `logged_out_user` = '$user_id'");
		
		while ($basket = mysqli_fetch_array($get_baskets)){
			$product = explode(";",$basket['items']);		
			foreach ($product as $findProducts){
				$productSetup = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `deal_id` = '$findProducts'");
				$productCount = mysqli_fetch_array($productSetup);
				if ($productCount['start_date'] < $today_date && $productCount['end_date'] > $today_date){
					$product_number++;
				}
			}
		}
	}

	if (isset($_SESSION['username'])){
		$username = $_SESSION['username'];
		
		$login_setup = mysqli_query($secure_db,"SELECT * FROM `members` WHERE `username` = '$username'");
		$login = mysqli_fetch_array($login_setup);
	}	
	
	include ('../includes/alerts.php');
?>