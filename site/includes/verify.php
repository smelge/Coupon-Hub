<?php
	session_start();
	include('./members_link.php');
	include('./basket_database.php');
	include('../evms/evms_basic.php');
	$homelink = 'index.php';
	
	$user = filter_input(INPUT_POST, 'user', FILTER_SANITIZE_SPECIAL_CHARS);
	
	$login_setup = mysqli_query($secure_db,"SELECT * FROM `members` WHERE `username` = '$user'");
	$user_exists = mysqli_num_rows($login_setup);
	$login = mysqli_fetch_array($login_setup);
	$company_setup = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `username` = '$user'");
	$company = mysqli_fetch_array($company_setup);
	
	$salt = $login['salty'];
	$password = $_POST['password'];
	$encrypted = crypt($password,$salt);
	
	if($user_exists == true){
		if($login['verified'] != 0){
			if ($encrypted == $login['password']){
				//Login Correct
				$_SESSION['username'] = $login['username'];
				$_SESSION['user_id'] = $login['user_id'];
				$_SESSION['usergroup'] = $login['usertype'];
				$_SESSION['usertype'] = $login['usertype'];
				
				// If cookies are for different usergroup to login, change them
				
				if ($_SESSION['usergroup'] == 2){
					// Usergroup for session is Vendor
					if($_COOKIE['CH-userdata'] != 'vendor-index'){
						setcookie('CH-userdata','vendor-index',time() + (86400 * 30), '/');
					}							
				} else {
					// Usergroup is Customer
					if($_COOKIE['CH-userdata'] == 'vendor-index'){
						setcookie('CH-userdata','user-index',time() + (86400 * 30), '/');
					}					
				}				
							
				if ($login['company'] == true){
					$_SESSION['company'] = $login['company'];					
					$_SESSION['company_id'] = $company['vendor_id'];
				}
				
				// Logged In, transfer any baskets to User ID				
				$userIP = $_SERVER['REMOTE_ADDR'];
				$getURegBasket = mysqli_query($db_basket,"SELECT * FROM `shopping_basket` WHERE `logged_out_user` = '$userIP'");				
				if (mysqli_num_rows($getURegBasket) == 1){
					// Found an unregistered basket for this IP
					$userID = $_SESSION['user_id'];
					$URegBasket = mysqli_fetch_array($URegBasket);
					
					$updateBasket = "UPDATE `shopping_basket` SET `user_id` = '$userID', `logged_out_user` = '0' WHERE `logged_out_user` = '$userIP'";
					mysqli_query($evms_db,"UPDATE `coupon_repo` SET `customer_id` = '$userID' WHERE `customer_id` = '$userIP'");
					mysqli_query($db_basket,$updateBasket);
				}
				
				header('Location: ../'.$homelink.'?result=1');
			} else {
				//Login Failed
				header('Location: ../'.$homelink.'?result=2');
			}
		} else {
			//User hasn't done email verification yet
			header('Location: ../'.$homelink.'?result=15');
		}
	} else {
		//User doesn't exist
		header('Location: ../'.$homelink.'?result=18');
	}
?>