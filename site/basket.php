<?php
	include_once('./includes/security.php');
	// Set up Sessions
	
	$pageTitle = 'The Coupon Hub';
	$metaKeywords = '';
	
	$delItem = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
	if ($delItem == true){
		// Delete Item from basket and refresh page
		if (isset($_SESSION['username'])){
			$modifyBasketGet = mysqli_query($db_basket,"SELECT * FROM `shopping_basket` WHERE `user_id` = '$user_id'");
		} else {
			$modifyBasketGet = mysqli_query($db_basket,"SELECT * FROM `shopping_basket` WHERE `logged_out_user` = '$user_id'");
		}
		$modifyBasket = mysqli_fetch_array($modifyBasketGet);
		$updatedBasket = str_ireplace($delItem.";","",$modifyBasket['items']);
		
		// Find related coupon id and remove
		$updateCoupons = explode(";",$modifyBasket['coupons']);
		foreach ($updateCoupons as $findCoupon){
			if ($findCoupon != ''){
				$locateCouponSet = mysqli_query($evms_db,"SELECT * FROM `coupon_repo` WHERE `customer_id` = '$user_id' AND `deal_id` = '$delItem' AND `coupon_id` = '$findCoupon'");
				if (mysqli_num_rows($locateCouponSet) != 0){
					$foundCoupon = $findCoupon;
				}
			}
		}
		$updatedCoupons = str_ireplace($foundCoupon.";","",$modifyBasket['coupons']);
		
		// Update DB
		$basketID = $modifyBasket['basket_id'];
		
		if ($updatedBasket == ''){
			// Last item deleted, remove basket from DB
			$updater = "DELETE FROM `shopping_basket` WHERE `basket_id` = '$basketID'";
		} else {
			$updater = "UPDATE `shopping_basket` SET `items` = '$updatedBasket', `coupons` = '$updatedCoupons' WHERE `basket_id` = '$basketID'";
		}
		
		$returnCoupon = "UPDATE `coupon_repo` SET `status` = '0', `customer_id` = '0' WHERE `coupon_id` = '$foundCoupon'";
		
		if (!mysqli_query($db_basket,$updater) || !mysqli_query($evms_db,$returnCoupon)){
			echo mysqli_error();
		} else {
			header('Location: ./basket.php');
		}
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<?php
			include_once('./includes/meta.php');			
			include_once('./includes/scripts.php');					
		?>		
	</head>
	<body class="container-fluid <?php echo $indexBg;?>">
		<!-- START CONTENT -->
		<?php include ('./includes/'.$indexNav.'.php');?>		
		<div class="row toprow pad">
			<!--<div class="col-sm-4 profile-column">-->
			<div class="col-sm-6 standard flat-right">
				<div class="form-heading">
					Current Items
				</div>
				<div class="content-bg">
					<?php
						if (isset($_SESSION['username'])){
							// Logged in User
							$currBasketQuery = "SELECT * FROM `shopping_basket` WHERE `user_id` = '$user_id'";
						} else {
							// Logged Out User
							$currBasketQuery = "SELECT * FROM `shopping_basket` WHERE `logged_out_user` = '$user_id'";
						}
						$currentBasketGet = mysqli_query ($db_basket,$currBasketQuery);
						if (mysqli_num_rows($currentBasketGet) == 0){
							echo '<div class="row pad">';
								echo '<div class="col-sm-12 empty">';
									echo 'Your Basket is Empty';
								echo '</div>';
							echo '</div>';
						} else {
							$totalDiscount = 0;
							$totalCost = 0;
							
							while($currentBasket = mysqli_fetch_array($currentBasketGet)){
								$currentItems = explode(";",$currentBasket['items']);
								foreach ($currentItems as $item_id){
									$item_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `deal_id` = '$item_id'");
									$item = mysqli_fetch_array($item_setup);
									if ($item_id == true){
										if ($item['start_date'] <= $today_date && $item['end_date'] >= $today_date){
											// Item is in date
											echo '<div class="row pad">';
												echo '<div class="col-sm-12 basket-item">';
													echo '<a class="no-link" href="./shop.php?item='.$item['deal_id'].'">';										
														echo '<div class="col-sm-3 basket-image">';
															echo '<img class="img-responsive" src="./assets/item-images/'.$item['image'].'" alt="'.$item['title'].'">';
														echo '</div>';
													echo '</a>';
													echo '<div class="col-sm-9 basket-body">';
														echo '<h4 class="media-heading">';
															echo '<a class="no-link" href="./shop.php?item='.$item['deal_id'].'">'.$item['title'].'</a>';
															echo '<a class="no-link" href="./basket.php?id='.$item['deal_id'].'"><i class="fa fa-trash pull-right"></i></a>';
														echo '</h4>';
														echo '&pound;'.$item['discount'].' - '.$item['percent'].'% off</br>';
														echo 'Valid until '.date("jS M Y",strtotime($item['end_date']));
													echo '</div>';
												echo '</div>';
											echo '</div>';
											$saving = $item['full_price'] - $item['discount'];
											$totalDiscount = $totalDiscount + $saving;
											$totalCost = $totalCost + $item['discount'];
										} else {
											// Item is out of date
											echo '<div class="row pad">';
												echo '<div class="col-sm-12 basket-item bg-danger">';
													echo '<a class="no-link" href="./shop.php?item='.$item['deal_id'].'">';										
														echo '<div class="col-sm-3 basket-image">';
															echo '<img class="img-responsive" src="./assets/item-images/'.$item['image'].'" alt="'.$item['title'].'">';
														echo '</div>';
													echo '</a>';
													echo '<div class="col-sm-9 basket-body">';
														echo '<h4 class="media-heading">';
															echo $item['title'];
															echo '<i class="fa fa-trash pull-right"></i>';
														echo '</h4>';
														echo 'Sorry, this offer has expired';
													echo '</div>';
												echo '</div>';
											echo '</div>';
										}									
									}							
								}
							}
							
							
							echo '<div class="row pad">';
								echo '<div class="col-sm-12 basket-item" style="text-align:center;">';
									echo '<h4 class="media-heading" style="margin: 5px 5px -15px 5px;">You will save - &pound;'.$totalDiscount.'</h4></br>';
									echo '<h3 class="media-heading">Total Cost - &pound;'.$totalCost.'</h3>';
								echo '</div>';
							echo '</div>';
							echo '<div class="col-sm-12 btn-group-vertical padding" style="border: 1px solid #3E4B67;">';
								echo '<a class="btn btn-default btn-lg btn-block" href="./shop.php">Continue Shopping</a>';
								if (isset($_SESSION['username'])){
									echo '<a class="btn btn-success btn-lg btn-block" href="./checkout.php">Checkout</a>';
								} else {
									echo '<a class="btn btn-success btn-lg btn-block" href="#" data-toggle="modal" data-target="#checkoutLoginModal">Register or Log In</a>';
								}
								echo '<a class="btn btn-primary btn-lg btn-block" href="#">Share Your Basket <i class="fa fa-facebook-square"></i></a>';
								echo '<a class="btn btn-info btn-lg btn-block" href="#">Share Your Basket <i class="fa fa-twitter-square"></i></a>';
							echo '</div>';
						}						
					?>
				</div>
			</div>
			<!--<div class="col-sm-4 profile-column">-->
			<div class="col-sm-6 standard flat-left">
				<div class="form-heading">
					Items to be Redeemed
				</div>
				<div class="content-bg">
					<?php
						if (isset($_SESSION['username'])){
							// Logged in User
							$currBasketQuery = "SELECT * FROM `redeemed_baskets` WHERE `user_id` = '$user_id'";
							
							$currentBasketGet = mysqli_query ($db_basket,$currBasketQuery);
							if (mysqli_num_rows($currentBasketGet) == 0){
								echo '<div class="row pad">';
									echo '<div class="col-sm-12 empty">';
										echo 'You have no coupons to Redeem';
									echo '</div>';
								echo '</div>';
							} else {
								$totalDiscount = 0;
								$totalCost = 0;
								
								while($currentBasket = mysqli_fetch_array($currentBasketGet)){
									//print_r($currentBasket);
									$currentItems = explode(";",$currentBasket['coupons']);
									foreach ($currentItems as $coupon_id){										
										if ($coupon_id == true){											
											$checkStatus = mysqli_query($evms_db,"SELECT * FROM `coupon_repo` WHERE `coupon_id` = '$coupon_id'");
											$displayStatus = mysqli_fetch_array($checkStatus);
											
											if ($displayStatus['status'] == 1){
												// Coupon has not been paid for, user must visit physical location, display voucher code
												$item_id = $displayStatus['deal_id'];
												$item_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `deal_id` = '$item_id'");
												$item = mysqli_fetch_array($item_setup);
												
												if ($item['start_date'] < $today_date && $item['end_date'] > $today_date){
													// Item is in date	
													echo '<div class="row pad">';
														echo '<div class="col-sm-12 basket-redeemed">';
															echo '<a class="no-link" href="./shop.php?item='.$item['deal_id'].'">';										
																echo '<div class="col-sm-3 basket-image">';
																	echo '<img class="img-responsive" src="./assets/item-images/'.$item['image'].'" alt="'.$item['title'].'">';
																echo '</div>';
															echo '</a>';
															echo '<div class="col-sm-9 basket-body">';
																echo '<h4 class="media-heading">';
																	echo '<a class="no-link" href="./shop.php?item='.$item['deal_id'].'">'.$item['title'].'</a>';
																echo '</h4>';
																echo '&pound;'.$item['discount'].' - '.$item['percent'].'% off</br>';
																echo 'Valid until '.date("jS M Y",strtotime($item['end_date']));
															echo '</div>';
														echo '</div>';
														echo '<div class="col-sm-12 basket-code">';
															echo 'Code: '.$displayStatus['serial'];
														echo '</div>';
													echo '</div>';
													$saving = $item['full_price'] - $item['discount'];
													$totalDiscount = $totalDiscount + $saving;
													$totalCost = $totalCost + $item['discount'];
												} else {
													// Item is out of date
													echo '<div class="row pad">';
														echo '<div class="col-sm-12 basket-item bg-danger">';
															echo '<a class="no-link" href="./shop.php?item='.$item['deal_id'].'">';										
																echo '<div class="col-sm-3 basket-image">';
																	echo '<img class="img-responsive" src="./assets/item-images/'.$item['image'].'" alt="'.$item['title'].'">';
																echo '</div>';
															echo '</a>';
															echo '<div class="col-sm-9 basket-body">';
																echo '<h4 class="media-heading">';
																	echo $item['title'];
																echo '</h4>';
																echo 'Sorry, this offer has expired';
															echo '</div>';
														echo '</div>';
													echo '</div>';
												}	
											} elseif ($displayStatus['status'] == 3){
												// Coupon has already been redeemed, ignore
											} elseif ($displayStatus['status'] == 4){
												// Coupon has been purchased, display as in progress
												// ONsite payments not yet implemented
												//echo 'Not implemented</br>';
											} else {
												// Something went wrong.
											}
										}
									}
								}
								
								
								echo '<div class="row pad">';
									echo '<div class="col-sm-12 basket-item" style="text-align:center;">';
										echo '<h4 class="media-heading" style="margin: 5px 5px -15px 5px;">You will save - &pound;'.$totalDiscount.'</h4></br>';
										echo '<h3 class="media-heading">Total Cost - &pound;'.$totalCost.'</h3>';
									echo '</div>';
								echo '</div>';
							}
						} else {
							// Logged Out User
							echo '<div class="row pad">';
								echo '<div class="col-sm-12 empty">';
									echo 'You need to be Logged In to view your coupons.</br></br>';
								echo '</div>';
							echo '</div>';							
						}						
					?>
				</div>
			</div>
			<!--
			<div class="col-sm-4 profile-column">
				<div class="profile-heading">
					Old Orders
				</div>
				<div class="standard">
					#
				</div>
			</div>
			-->
		</div>
		<?php include ('./includes/footer.php');?>		
		<!-- END CONTENT -->
	</body>
</html>