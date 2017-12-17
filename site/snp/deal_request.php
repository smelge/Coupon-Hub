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
			
			$request_setup = mysqli_query($secure_db,"SELECT * FROM `SNP_deal_links` WHERE `partnerID` = '$vendor_id' AND `agreed` = 0");
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
				<a class="btn btn-default active">Deal Requests</a>
				<a class="btn btn-default" href="./review_deals.php">Review Deals</a>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="table-background">
					<table class="table table-bordered table-hover table-responsive" style="border:2px solid black;">
						<tr style="border:1px solid black;">
							<th style="border:1px solid black;">Deal</th>
							<th style="border:1px solid black;">Company</th>
							<th style="border:1px solid black;">Category</th>
							<th style="border:1px solid black;">Price</th>
							<th style="border:1px solid black;">End Date</th>
							<th style="border:1px solid black;">View Deal</th>
							<th style="border:1px solid black;">Accept Deal Partnership</th>
							<th style="border:1px solid black;">Decline Deal Partnership</th>
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
								
								$formatExpiry = date("jS F Y",strtotime($dealDetails['expiry']));
								
								echo '<tr>';									
									echo '<td style="border:1px solid black;">'.$itemDetails['title'].'</td>';
									echo '<td style="border:1px solid black;">'.$partnerDetail['company'].'</td>';
									echo '<td style="border:1px solid black;">'.getCategory($itemDetails['category']).'</td>';
									echo '<td style="border:1px solid black;">&pound;'.$itemDetails['discount'].'</td>';
									echo '<td style="border:1px solid black;">'.$formatExpiry.'</td>';
									echo '<td style="border:1px solid black;"><a style="width:100%" class="btn btn-default" href="../shop.php?item='.$dealDetails['item_id'].'" target="_blank">Offer</td>';
									echo '<td style="border:1px solid black;"><a style="width:100%" class="btn btn-success" href="./includes/dealProcess.php?a=yes&d='.$request['id'].'">Accept</a></td>';
									echo '<td style="border:1px solid black;"><a style="width:100%" class="btn btn-danger" href="./includes/dealProcess.php?a=no&d='.$request['id'].'">Decline</a></td>';
								echo '</tr>';
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