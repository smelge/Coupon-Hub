<?php
	// Set up Sessions
	include_once('./includes/snp_security.php');
	//Restricted page, check for eligibility, if not send to index
	if (!isset($_SESSION['company'])){
		header('Location: ../'.$homelink);		
	}
	
	$page = 'View your Social Network Partnership Requests';		
	$company_name = $_SESSION['company'];
	$company_set = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `company` = '$company_name'");
	$company = mysqli_fetch_array($company_set);
	$vendor_id = $company['vendor_id'];	
	
	$page1form = 	'
						<div class="col-sm-6 col-sm-offset-3">							
							<div class="standard">
								<div class="form-heading">
									Select an Offer
								</div>
								<form action="./snp_deal.php" method="POST">
									<input type="hidden" name="page" value="2"/>
					';
							
	$list_offers_set = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `vendor_id` = '$vendor_id' AND ('$today_date' > `start_date` AND '$today_date' <= `end_date`)");
	if (mysqli_num_rows($list_offers_set) == 0){
		$page1form .= '<a class="btn btn-lg btn-danger btn-block" href="../myaccount.php">You have no Offers currently running</a>';
	} else {
		while ($list_offers = mysqli_fetch_array($list_offers_set)){
			$page1form .= '<input class="btn btn-default btn-lg btn-block" type="submit" name ="sendOffer" value="'.$list_offers['title'].'"/>';
		}
	}
	
	$page1form .= '</form></div></div>';
	
	if (!isset($_POST['sendOffer'])){
		$sendOffer = '0';
	} else {
		$sendOffer = $_POST['sendOffer'];
		$findOfferSet = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `title` = '$sendOffer'");
		$findOffer = mysqli_fetch_array($findOfferSet);
		$itemID = $findOffer['deal_id'];
		$expiryDate = $findOffer['end_date'];
					
		$page2form =	'
							<div class="row">
								<div class="col-sm-6 col-sm-offset-3">
									<div class="col-sm-12 basket-item" style="background:#CED0D5;margin-bottom:10px;">									
										<div class="col-sm-3 basket-image">
											<img class="img-responsive" src="../assets/item-images/'.$findOffer['image'].'" alt="'.$findOffer['title'].'">
										</div>
										<div class="col-sm-9 basket-body">
											<h4 class="media-heading">
												'.$findOffer['title'].'
											</h4>
											&pound;'.$findOffer['discount'].' - '.$findOffer['percent'].'% off</br>
											Valid until '.date("jS M Y",strtotime($findOffer['end_date'])).'
										</div>
									</div>									
									<div class="standard">
										<div class="form-heading">
											Select Partners to share Offer
										</div>
										<form action="./includes/setupDeal.php" method="POST">
											<input type="hidden" name="offer" value="'.$itemID.'"/>
											<input type="hidden" name="vendor" value="'.$vendor_id.'"/>
											<input type="hidden" name="expiry" value="'.$expiryDate.'"/>
						';
								
		
		$partners_set = mysqli_query($secure_db,"SELECT * FROM `social_network_partners` WHERE (`vendor_id` = '$vendor_id' OR `partner_id` = '$vendor_id') AND `approved` = '1'");
		$partnerLoop = 0;
		while ($partners = mysqli_fetch_array($partners_set)){
			$partnerLoop++;
			if ($partners['vendor_id'] == $vendor_id){
				$curr_partner = $partners['partner_id'];
			} else {
				$curr_partner = $partners['vendor_id'];
			}						
			$find_partner_set = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `vendor_id` = '$curr_partner'");
			$find_partner = mysqli_fetch_array($find_partner_set);
			$page2form .= '<input type="checkbox" name="partner'.$partnerLoop.'" value="'.$find_partner['vendor_id'].'"> '.$find_partner['company'].'</br>';
		}
		
		$page2form .= '
			<input type="hidden" name="partnerNo" value="'.$partnerLoop.'"/>
			<hr>
			<input type="submit" class="btn btn-success btn-lg btn-block" value="Set up this Deal"/>
			<a class="btn btn-danger btn-lg btn-block" href="./snp_deal.php"/>Select a new offer</a>
		';
		$page2form .= '</form></div></div></div>';
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
	<body class="container-fluid vendor-body">
		<!-- START CONTENT -->
		<?php include ('./includes/snp-header.php');?>
		<!-- SNP Menu -->
		<div class="row">
			<div class="col-sm-12 vendor-nav btn-group topmargin bottommargin">
				<a class="btn btn-default" href="./partnerships.php">Partnerships</a>
				<a class="btn btn-default" href="./request.php">View Requests</a>
				<a class="btn btn-default" href="./find_partner.php">Add Partner</a>
				<a class="btn btn-default active">Set up Deal</a>
				<a class="btn btn-default" href="./deal_request.php">Deal Requests</a>
				<a class="btn btn-default" href="./review_deals.php">Review Deals</a>
			</div>
		</div>
		<div class="row">
			<?php
				if (!isset($_POST['page'])){
					// First page
					
					// Display list of current offers with at least a week until end
					// Checkboxes for each offer
					// reload as page 2				
					echo '</br>'.$page1form;
				} else {
					// Second page
					
					// Select partners
					// Display list of partners with tickbox
					// Ok button
					echo '</br>'.$page2form;
				}
			?>
		</div>		
		<?php include ('../includes/footer.php');?>		
		<!-- END CONTENT -->
	</body>
</html>