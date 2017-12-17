<?php
	// Set up Sessions
	include_once('./includes/snp_security.php');
	//Restricted page, check for eligibility, if not send to index
	if (!isset($_SESSION['company'])){
		header('Location: ../'.$homelink);		
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php
			include_once('./includes/meta.php');			
			include_once('./includes/scripts.php');
		
			$page = 'View your Social Network Partnership Deal Requests';		
			$company_name = $_SESSION['company'];
			$company_set = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `company` = '$company_name'");
			$company = mysqli_fetch_array($company_set);
			$vendor_id = $company['vendor_id'];		
		?>
	</head>
	
	<body class="container-fluid vendor-body">
		<!-- START CONTENT -->
		<?php include ('./includes/snp-header.php');?>
		<!-- SNP Menu -->
		<div class="row">
			<div class="col-sm-12 vendor-nav btn-group topmargin bottommargin">
				<a class="btn btn-default" href="./partnerships.php">Partnerships</a>
				<a class="btn btn-default" href="./request.php">View Requests</a>
				<a class="btn btn-default" href="./find_partner.php">Add Partner</a>
				<a class="btn btn-default" href="./snp_deal.php">Set up Deal</a>
				<a class="btn btn-default" href="./deal_request.php">Deal Requests</a>
				<a class="btn btn-default active">Review Deals</a>
			</div>
		</div>
		
		<?php
			$request_setup = mysqli_query($secure_db,"SELECT * FROM `SNP_deal_links` WHERE `partnerID` = '$vendor_id' AND `agreed` = 1");
		?>
		<div class="row">
			<div class="col-sm-12">
				<div class="table-background margin-top">
					<table class="table table-bordered table-hover table-responsive" style="border:2px solid black;">	
						<tr style="border-bottom:2px solid black;">
							<th colspan="3" style="background:#ffffff;">Deals with Me</th>
						</tr>
						<?php
							while ($request = mysqli_fetch_array($request_setup)){								
								$find_dealID = $request['dealId'];
								$dealDetails_set = mysqli_query($secure_db,"SELECT * FROM `social_network_deals` WHERE `dealID` = '$find_dealID'");
								$dealDetails = mysqli_fetch_array($dealDetails_set);
								
								$findPartner = $dealDetails['vendor_id'];								
								$partnerDetail_set = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `vendor_id` = '$findPartner'");
								$partnerDetail = mysqli_fetch_array($partnerDetail_set);
								
								$itemFind = $dealDetails['item_id'];
								$itemDetails_set = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `deal_id` = '$itemFind'");
								$itemDetails = mysqli_fetch_array($itemDetails_set);
								
								if ($dealDetails['expiry'] < $today_date){
									echo '<tr style="border-bottom:2px solid black;">';									
										echo '<td width="10%" style="border:1px solid black;background:#ffffff;padding:0;"><a href="../shop.php?item='.$itemDetails['deal_id'].'" target="_blank"><img class="img-responsive center-block" style="max-height:92px;" src="../assets/item-images/'.$itemDetails['image'].'" alt="'.$itemDetails['title'].'"/></a></td>';
										echo '<td width="40%" style="border:1px solid black;">'.$itemDetails['title'].' - &pound;'.$itemDetails['discount'].'<hr>'.$partnerDetail['company'].' - <i>Expired: '.dateEdit($dealDetails['expiry']).'</i></td>';
										echo '<td width="50%" style="border:1px solid black;">Sharing Statistics</br>Coming Soon</td>';											
									echo '</tr>';
								} else {
									echo '<tr style="border-bottom:2px solid black;">';									
										echo '<td width="10%" style="border:1px solid black;background:#ffffff;padding:0;"><a href="../shop.php?item='.$itemDetails['deal_id'].'" target="_blank"><img class="img-responsive center-block" style="max-height:92px;" src="../assets/item-images/'.$itemDetails['image'].'" alt="'.$itemDetails['title'].'"/></a></td>';
										echo '<td width="40%" style="border:1px solid black;">'.$itemDetails['title'].' - &pound;'.$itemDetails['discount'].'<hr>'.$partnerDetail['company'].' - <i>Expires: '.dateEdit($dealDetails['expiry']).'</i></td>';
										echo '<td width="50%" style="border:1px solid black;padding:0;background:#000;">
											<a style="width:100%" class="btn btn-primary btn-lg" href="../shop.php?item='.$itemDetails['deal_id'].'&ref='.$request['dealId'].'" target="_blank">Share to Facebook (Shared '.$request['sharedFacebook'].' Times - Last Shared: '.dateEdit($request['lastShareFacebook']).')</a>
											<a style="width:100%" class="btn btn-info btn-lg" href="../shop.php?item='.$itemDetails['deal_id'].'&ref='.$request['dealId'].'" target="_blank">Share to Twitter (Shared '.$request['sharedTwitter'].' Times - Last Shared: '.dateEdit($request['lastShareTwitter']).')</a>
										</td>';
									echo '</tr>';
								}
								
							}
						?>
					</table>
				</div>
			</div>
		</div>
		<?php
			$myRequest_setup = mysqli_query($secure_db,"SELECT * FROM `social_network_deals` WHERE `vendor_id` = '$vendor_id'");
		?>
		<div class="row">
			<div class="col-sm-12">
				<div class="table-background margin-top">
					<table class="table table-bordered table-hover table-responsive" style="border:2px solid black;">	
						<tr style="border-bottom:2px solid black;">
							<th colspan="6" style="background:#ffffff;">My Deals</th>
						</tr>
						<?php
							while ($myRequest = mysqli_fetch_array($myRequest_setup)){
								$myFind_dealID = $myRequest['dealId'];
								//$myDealDetails_set = mysqli_query($secure_db,"SELECT * FROM `social_network_deals` WHERE `dealID` = '$myFind_dealID'");
								//$myDealDetails = mysqli_fetch_array($myDealDetails_set);
								
								$myItemFind = $myRequest['item_id'];
								$myItemDetails_set = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `deal_id` = '$myItemFind'");
								$myItemDetails = mysqli_fetch_array($myItemDetails_set);
								
								$myPartners_set = mysqli_query($secure_db,"SELECT * FROM `SNP_deal_links` WHERE `dealId` = '$myFind_dealID'");
								$myPartnersRows = mysqli_num_rows($myPartners_set);
								
								echo '<tr style="border-bottom:2px solid black;background:#fff">';	
									echo '<th style="border:1px solid black;">Deal</th>';
									echo '<th style="border:1px solid black;">Expiry</th>';
									echo '<th style="border:1px solid black;">Shared With</th>';
									echo '<th style="border:1px solid black;">Accepted?</th>';
									echo '<th style="border:1px solid black;">Facebook Shares</th>';
									echo '<th style="border:1px solid black;">Twitter Shares</th>';
								echo '</tr>';
								
								if ($myPartnersRows == 0){
									echo '<tr style="border-bottom:2px solid black;background:#fff">';	
										echo '<th colspan="6" style="border:1px solid black;">No Deals Yet</th>';
									echo '</tr>';
								} elseif ($myPartnersRows == 1){
									$myPartners = mysqli_fetch_array($myPartners_set);
									echo '<tr style="border-bottom:2px solid black;">';
										echo '<td rowspan="'.$myPartnersRows.'" style="border:1px solid black;background:#ffffff;padding:0;"><a href="../shop.php?item='.$myItemDetails['deal_id'].'" target="_blank"><img class="img-responsive center-block" style="max-height:92px;" src="../assets/item-images/'.$myItemDetails['image'].'" alt="'.$myItemDetails['title'].'"/></a></td>';
										echo '<td rowspan="'.$myPartnersRows.'" style="border:1px solid black;">'.dateEdit($myRequest['expiry']).'</td>';
										echo '<td style="border:1px solid black;">'.getVendor($myPartners['partnerId']).'</td>';
										if ($myPartners['agreed'] == 0){
											echo '<td class="bg-warning" style="border:1px solid black;">Pending</td>';
											echo '<td class="bg-warning" style="border:1px solid black;">-</td>';
											echo '<td class="bg-warning" style="border:1px solid black;">-</td>';
										} elseif ($myPartners['agreed'] == 1){
											echo '<td class="bg-success" style="border:1px solid black;">Accepted '.dateEdit($myPartners['dateActivated']).'</td>';
											echo '<td style="border:1px solid black;">'.$myPartners['sharedFacebook'].' ('.dateEdit($myPartners['lastShareFacebook']).')</td>';
											echo '<td style="border:1px solid black;">'.$myPartners['sharedTwitter'].' ('.dateEdit($myPartners['lastShareTwitter']).')</td>';
										} else {
											echo '<td class="bg-danger" style="border:1px solid black;">Declined '.dateEdit($myPartners['dateActivated']).'</td>';
											echo '<td class="bg-danger" style="border:1px solid black;">n/a</td>';
											echo '<td class="bg-danger" style="border:1px solid black;">n/a</td>';
										}
									echo '</tr>';
								} else {
									echo '<tr style="border-bottom:1px solid black;">';
										echo '<td rowspan="'.$myPartnersRows.'" style="border:1px solid black;background:#ffffff;padding:0;"><a href="../shop.php?item='.$myItemDetails['deal_id'].'" target="_blank"><img class="img-responsive center-block" style="max-height:92px;" src="../assets/item-images/'.$myItemDetails['image'].'" alt="'.$myItemDetails['title'].'"/></a></td>';
										echo '<td rowspan="'.$myPartnersRows.'" style="border:1px solid black;">'.dateEdit($myRequest['expiry']).'</td>';
									// First column of While needs to be here
									$myPartnersLoop = 1;
									while ($myPartners = mysqli_fetch_array($myPartners_set)){
										if ($myPartnersLoop == 1){
											echo '<td style="border:1px solid black;">'.getVendor($myPartners['partnerId']).'</td>';
											if ($myPartners['agreed'] == 0){
												echo '<td class="bg-warning" style="border:1px solid black;">Pending</td>';
												echo '<td class="bg-warning" style="border:1px solid black;">-</td>';
												echo '<td class="bg-warning" style="border:1px solid black;">-</td>';
											} elseif ($myPartners['agreed'] == 1){
												echo '<td class="bg-success" style="border:1px solid black;">Accepted '.dateEdit($myPartners['dateActivated']).'</td>';
												echo '<td style="border:1px solid black;">'.$myPartners['sharedFacebook'].' ('.dateEdit($myPartners['lastShareFacebook']).')</td>';
												echo '<td style="border:1px solid black;">'.$myPartners['sharedTwitter'].' ('.dateEdit($myPartners['lastShareTwitter']).')</td>';
											} else {
												echo '<td class="bg-danger" style="border:1px solid black;">Declined '.dateEdit($myPartners['dateActivated']).'</td>';
												echo '<td class="bg-danger" style="border:1px solid black;">n/a</td>';
												echo '<td class="bg-danger" style="border:1px solid black;">n/a</td>';
											}											
											echo '</tr>';
										} else {
											echo '<tr style="border-bottom:2px solid black;">';
												echo '<td style="border:1px solid black;">'.getVendor($myPartners['partnerId']).'</td>';
												if ($myPartners['agreed'] == 0){
													echo '<td class="bg-warning" style="border:1px solid black;">Pending</td>';
													echo '<td class="bg-warning" style="border:1px solid black;">-</td>';
													echo '<td class="bg-warning" style="border:1px solid black;">-</td>';
												} elseif ($myPartners['agreed'] == 1){
													echo '<td class="bg-success" style="border:1px solid black;">Accepted '.dateEdit($myPartners['dateActivated']).'</td>';
													echo '<td style="border:1px solid black;">'.$myPartners['sharedFacebook'].' ('.dateEdit($myPartners['lastShareFacebook']).')</td>';
													echo '<td style="border:1px solid black;">'.$myPartners['sharedTwitter'].' ('.dateEdit($myPartners['lastShareTwitter']).')</td>';
												} else {
													echo '<td class="bg-danger" style="border:1px solid black;">Declined '.dateEdit($myPartners['dateActivated']).'</td>';
													echo '<td class="bg-danger" style="border:1px solid black;">n/a</td>';
													echo '<td class="bg-danger" style="border:1px solid black;">n/a</td>';
												}																				
											echo '</tr>';
										}
									}
								}	
							}
						?>
					</table>
				</div>
			</div>
		</div>
		<?php include ('../includes/footer.php');?>		
		<!-- END CONTENT -->
	</body>
</html>