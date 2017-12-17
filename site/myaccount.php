<?php
	include_once('./includes/security.php');
	// Set up Sessions
	if (!isset($_SESSION['username'])){
		header('Location: ./'.$homelink);		
	}
	$pageTitle = 'The Coupon Hub - Account';
	$metaKeywords = '';
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

		<?php
			// Check if user is a Customer or Vendor
			if (!isset($_SESSION['company_id'])){
				// No company id set, user is a customer ?>
				
				<div class="row pad toprow">
					<div class="col-sm-12 heading">
						User Control Panel
					</div>
				</div>
				<div class="row pad">
					<div class="col-sm-4 profile-column">						
						<div class="col-sm-12 standard">
							<div class="col-sm-12 form-heading">
								Profile
							</div>
							<div class="col-sm-12 padding-0">
								<?php
									if ($get_mail == 0){
										echo '<a class="btn btn-default btn-block" href="./mail.php">Inbox <span class="badge">'.$get_mail.'</span></a>';
									} else {
										echo '<a class="btn btn-danger btn-block" href="./mail.php">Inbox <span class="badge">'.$get_mail.'</span></a>';
									}
								?>	
								<a class="btn btn-default btn-block" href="./profile.php?id=<?php echo $_SESSION['user_id'];?>&t=u">View Profile</a>
							</div>
						</div>
						
						<div class="col-sm-12 padding-0 statistics topmargin">
							<div class="col-sm-8 stats-row">Balance</div>
							<div class="col-sm-4 stats-row">&pound;<?php echo $login['user_balance'];?></div>
							<!-- NOT YET IMPLEMENTED
							<div class="col-sm-8 stats-row">Charity Donations</div>
							<div class="col-sm-4 stats-row">&pound;#</div>
							-->
							<div class="col-sm-8 stats-row">Offers in Basket</div>
							<div class="col-sm-4 stats-row"><?php echo $product_number;?></div>
							<!-- NOT YET IMPLEMENTED
							<div class="col-sm-8 stats-row">Total Offers Redeemed</div>
							<div class="col-sm-4 stats-row">#</div>
							-->
							<!-- NOT YET IMPLEMENTED					
							<div class="col-sm-8 stats-row">Friends</div>
							<div class="col-sm-4 stats-row">#</div>
							-->
						</div>
					</div>			
					<div class="col-sm-8 profile-column">						
						<div class="col-sm-12 standard padding-0">
							<div class="col-sm-12 form-heading">
								Basket
							</div>
							<div class="col-sm-12 padding-0">
								<?php						
									$currentBasketGet = mysqli_query ($db_basket,"SELECT * FROM `shopping_basket` WHERE `user_id` = '$user_id'");
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
													if ($item['start_date'] < $today_date && $item['end_date'] > $today_date){
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
											echo '<div class="col-sm-12 basket-item" style="text-align:center; border-bottom:0px solid #fff;">';
												echo '<h4 class="media-heading" style="margin: 5px 5px -15px 5px;">You will save - &pound;'.$totalDiscount.'</h4></br>';
												echo '<h3 class="media-heading">Total Cost - &pound;'.$totalCost.'</h3>';
											echo '</div>';
										echo '</div>';
										echo '<div class="col-sm-12 btn-group-vertical padding">';
											echo '<a class="btn btn-success btn-lg btn-block" href="./checkout.php">Checkout</a>';								
											echo '<a class="btn btn-primary btn-lg btn-block" href="#">Share Your Basket <i class="fa fa-facebook-square"></i></a>';
											echo '<a class="btn btn-info btn-lg btn-block" href="#">Share Your Basket <i class="fa fa-twitter-square"></i></a>';
										echo '</div>';
									}
								?>
							</div>
						</div>
						<div class="col-sm-12 standard padding-0 topmargin">
							<div class="col-sm-12 form-heading">
								My Coupon Codes
							</div>
							<div class="col-sm-12 padding-0">
								<?php
									$myCodesSetup = mysqli_query($evms_db,"SELECT * FROM `coupon_repo` WHERE `customer_id` = '$user_id' AND `status` != 0");
									while ($myCodes = mysqli_fetch_array($myCodesSetup)){
										// Get details on this offer
										$dealIdent = $myCodes['deal_id'];
										$codeDetailsSet = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `deal_id` = '$dealIdent'");
										$codeDetails = mysqli_fetch_array($codeDetailsSet);
										
										echo '<div class="row pad">';
											echo '<div class="col-sm-12 basket-item">';
												echo '<a class="no-link" href="./shop.php?item='.$codeDetails['deal_id'].'">';										
													echo '<div class="col-sm-3 basket-image">';
														echo '<img class="img-responsive" src="./assets/item-images/'.$codeDetails['image'].'" alt="'.$codeDetails['title'].'">';
													echo '</div>';
												echo '</a>';
												echo '<div class="col-sm-9 basket-body">';
													echo '<h4 class="media-heading">';
														echo '<a class="no-link" href="./shop.php?item='.$codeDetails['deal_id'].'">'.$codeDetails['title'].'</a>';
													echo '</h4>';
													echo '&pound;'.$codeDetails['discount'].' ( '.$codeDetails['percent'].'% off)</br>';
													echo 'Valid until '.date("jS M Y",strtotime($codeDetails['end_date']));
													echo '<h4 class="media-heading">';
														if ($myCodes['status'] == 1){
															// Coupon Downloaded but unpaid for
															echo $myCodes['serial'] .' - Pay In-store';
														} elseif ($myCodes['status'] == 3){
															// Coupon has been redeemed
															echo 'Coupon Redeemed!';
														} elseif ($myCodes['status'] == 4){
															// Coupon has not been redeemed, but has been paid for
															echo $myCodes['serial'].' - Paid!';
														}														
												echo '</div>';
											echo '</div>';
										echo '</div>';
									}
								?>
							</div>
						</div>
					</div>
				</div>
				
				<?php
			} else {
				// Company id set, user is a Vendor 
				
				$company_name = $_SESSION['company'];
				$company_set = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `company` = '$company_name'");
				$company = mysqli_fetch_array($company_set);
				$vendor_id = $company['vendor_id'];
				
				$active_coupons = 0;
				$taken_coupons = 0;
				$redeemed_coupons = 0;
					
				$deals_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `vendor_id` = '$vendor_id'");
				$deals = mysqli_num_rows($deals_setup);
				while ($coupons = mysqli_fetch_array($deals_setup)){
					$deal_id = $coupons['deal_id'];
					$active_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_repo` WHERE `deal_id` = '$deal_id' AND `status` = 0");
					$active_coupons = $active_coupons + mysqli_num_rows($active_setup);
					$taken_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_repo` WHERE `deal_id` = '$deal_id' AND (`status` = 1 OR `status` = 2)");
					$taken_coupons = $taken_coupons + mysqli_num_rows($taken_setup);
					$redeemed_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_repo` WHERE `deal_id` = '$deal_id' AND `status` = 3");
					$redeemed_coupons = $redeemed_coupons + mysqli_num_rows($redeemed_setup);
				}
			
			/*	$deals_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `vendor_id` = '$vendor_id'");
				$active_deals = mysqli_num_rows($deals_setup);
				while ($deal_stats = mysqli_fetch_array){
					//Get active coupons
					$deal_id = $deal_stats['deal_id'];
					$active_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_repo` WHERE `deal_id` = '$deal_id' AND `status` = 0");
					$active_coupons = mysqli_num_rows($active_setup);
				}*/
				?>
				
				<div class="row pad equal toprow">
					<div class="col-sm-3 profile-column">						
						<div class="standard">
							<div class="form-heading">
								User Profile
							</div>
							<?php
								if ($get_mail == 0){
									echo '<a class="btn btn-default btn-block" href="./mail.php">Inbox <span class="badge">'.$get_mail.'</span></a>';
								} else {
									echo '<a class="btn btn-danger btn-block" href="./mail.php">Inbox <span class="badge">'.$get_mail.'</span></a>';
								}
							?>							
							<a class="btn btn-default btn-block" href="./profile.php?id=<?php echo $_SESSION['user_id'];?>&t=u">View Profile</a>
							<a class="btn btn-default btn-block" href="./edit_profile.php?id=<?php echo $_SESSION['user_id'];?>&t=v">Edit Profile</a>
						</div>						
						<div class="standard topmargin">
							<div class="form-heading">
								Company Profile
							</div>
							<a class="btn btn-default btn-block" href="./profile.php?id=<?php echo $vendor_id;?>&t=v">View Profile</a>
							<a class="btn btn-default btn-block" href="./edit_profile.php?id=<?php echo $vendor_id;?>&t=v">Edit Profile</a>
						</div>
					</div>
					<div class="col-sm-3 profile-column">						
						<div class="standard">
							<div class="form-heading">
								Voucher Management
							</div>
							<a class="btn btn-default btn-block" href="./evms_validate.php" <?php if ($deals == 0){echo 'disabled="disabled"';}?>>Manually Check Voucher Code</a>
							<a class="btn btn-default btn-block" href="./active_coupons.php"<?php if ($deals == 0){echo 'disabled="disabled"';}?>>View Active Coupons</a>
							<a class="btn btn-default btn-block" href="./add_offer.php">Add an Offer</a>
						</div>
					</div>
					<div class="col-sm-3 profile-column">						
						<div class="standard">
							<div class="form-heading">
								Social Network Partnership
							</div>
							<?php
								if ($company['twitter'] == true || $company['facebook'] == true){
									$snp_partnership_setup = mysqli_query($secure_db,"SELECT * FROM `social_network_partners` WHERE (`vendor_id` = '$vendor_id' OR `partner_id` = '$vendor_id') AND `approved` = 1");
									$snp_partnership = mysqli_num_rows($snp_partnership_setup);
									
									$snpDealRequests_setup = mysqli_query($secure_db,"SELECT * FROM `SNP_deal_links` WHERE `partnerID` = '$vendor_id' AND `agreed` = 0");
									$snpDealRequests = mysqli_num_rows($snpDealRequests_setup);
									
									$snpDealReview_setup = mysqli_query($secure_db,"SELECT * FROM `SNP_deal_links`, `social_network_deals` WHERE (`SNP_deal_links`.`partnerID` = '$vendor_id' AND `SNP_deal_links`.`agreed` = 1) OR `social_network_deals`.`vendor_id` = '$vendor_id'");
									$snpDealReview = mysqli_num_rows($snpDealReview_setup);
									
									if ($snp_partnership == 0){
										echo '<a class="btn btn-default btn-block" href="./snp/partnerships.php">No Partnerships</a>';
									} elseif ($snp_partnership == 1){
										echo '<a class="btn btn-default btn-block" href="./snp/partnerships.php">'.$snp_partnership.' Partnership</a>';
									} else {
										echo '<a class="btn btn-default btn-block" href="./snp/partnerships.php">'.$snp_partnership.' Partnerships</a>';
									}
									$snp_request_setup = mysqli_query($secure_db,"SELECT * FROM `social_network_partners` WHERE `partner_id` = '$vendor_id' AND `approved` = 0");
									$snp_request = mysqli_num_rows($snp_request_setup);
									
									if ($snp_request == 0){
										echo '<a class="btn btn-default btn-block" href="./snp/request.php">No Requests</a>';
									} else {
										echo '<a class="btn btn-success btn-block" href="./snp/request.php">'.$snp_request.' Requests</a>';
									}
									echo '
										<a class="btn btn-default btn-block" href="./snp/find_partner.php">Add a Partner</a>
										<a class="btn btn-default btn-block" href="./snp/snp_deal.php">Set up a Deal</a>
									';	
									if ($snpDealRequests == 0){
										echo '<a class="btn btn-default btn-block" href="./snp/deal_request.php">Deal Requests</a>';
									} elseif ($snpDealRequests == 1){
										echo '<a class="btn btn-danger btn-block" href="./snp/deal_request.php">'.$snpDealRequests.' Deal Request</a>';
									} else {
										echo '<a class="btn btn-danger btn-block" href="./snp/deal_request.php">'.$snpDealRequests.' Deal Requests</a>';
									}
										
									if ($snpDealReview == 0){
										echo '<a class="btn btn-default btn-block" href="./snp/review_deals.php">Review & Post Deals</a>';
									} else {
										echo '<a class="btn btn-success btn-block" href="./snp/review_deals.php">Review & Post Deals</a>';
									}
									
									echo '									
										<a class="btn btn-default btn-block" href="#" disabled="disabled">Recommend The Coupon Hub</a>
									';
								} else {
									echo '<a class="btn btn-default btn-block" href="./edit_profile.php?id='.$vendor_id.'&t=v">Add Social media</a>';
								}
							?>
						</div>
					</div>
					<div class="col-sm-3 profile-column">						
						<div class="standard">
							<div class="form-heading">
								Statistics
							</div>
							<div class="col-sm-8 stats-left">Active Deals</div>
							<div class="col-sm-4 stats-right"><?php echo $deals;?></div>
							<div class="col-sm-8 stats-left">Active Coupons</div>
							<div class="col-sm-4 stats-right"><?php echo $active_coupons;?></div>
							<div class="col-sm-8 stats-left">Coupons Taken</div>
							<div class="col-sm-4 stats-right"><?php echo $taken_coupons;?></div>
							<div class="col-sm-8 stats-left">Coupons Redeemed</div>
							<div class="col-sm-4 stats-right"><?php echo $redeemed_coupons;?></div>
							<div class="col-sm-8 stats-left">Balance</div>
							<div class="col-sm-4 stats-right">&pound;<?php echo $company['balance'];?></div>
							<br style="clear:both;">
						</div>				
						<div class="standard topmargin">
							<a class="btn btn-default btn-block" href="#" disabled="disabled">Account History</a>
							<a class="btn btn-default btn-block" href="#" disabled="disabled">Transaction History</a>
						</div>
					</div>
				</div>	
				
				<?php
			}
		?>
		
		<?php include ('./includes/footer.php');?>		
		<!-- END CONTENT -->
	</body>
</html>