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
		
			$page = 'View your Social Network Partnership Requests';		
			$company_name = $_SESSION['company'];
			$company_set = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `company` = '$company_name'");
			$company = mysqli_fetch_array($company_set);
			$vendor_id = $company['vendor_id'];
			
			$request_setup = mysqli_query($secure_db,"SELECT * FROM `social_network_partners` WHERE `partner_id` = '$vendor_id' AND `approved` = 0");
			
			//Get Facebook Likes Count of a page
			function fbStuff($id,$appid,$appsecret){
				//Construct a Facebook URL
				$json_url ='https://graph.facebook.com/'.$id.'?access_token='.$appid.'|'.$appsecret.'&fields=likes,talking_about_count,were_here_count';
				$json = file_get_contents($json_url);
				$json_output = json_decode($json,true);
							 
				//Extract the likes count from the JSON object
				$fb_likes = $json_output['likes'];
				$fb_talk = $json_output['talking_about_count'];
				$fb_visits = $json_output['were_here_count'];
				return array($fb_likes,$fb_talk,$fb_visits);				
			}	
		?>
	</head>
	<body class="container-fluid vendor-body">
		<!-- START CONTENT -->
		<?php include ('./includes/snp-header.php');?>
		<!-- SNP Menu -->
		<div class="row">
			<div class="col-sm-12 vendor-nav btn-group topmargin bottommargin">
				<a class="btn btn-default" href="./partnerships.php">Partnerships</a>
				<a class="btn btn-default active">View Requests</a>
				<a class="btn btn-default" href="./find_partner.php">Add Partner</a>
				<a class="btn btn-default" href="./snp_deal.php">Set up Deal</a>
				<a class="btn btn-default" href="./deal_request.php">Deal Requests</a>
				<a class="btn btn-default" href="./review_deals.php">Review Deals</a>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="table-background topmargin">
					<table class="table table-bordered table-hover table-responsive" style="border:2px solid black;">
						<tr style="border:1px solid black;">
							<th style="border:1px solid black;">Partner</th>
							<th style="border:1px solid black;">Associated User</th>
							<th style="border:1px solid black;">Category</th>
							<th style="border:1px solid black;">Facebook Likes</th>
							<th style="border:1px solid black;">Facebook Talking About</th>
							<th style="border:1px solid black;">Facebook Views</th>
							<th style="border:1px solid black;">Twitter Followers</th>
							<th style="border:1px solid black;">View Profile</th>
							<th style="border:1px solid black;">Accept Partnership</th>
							<th style="border:1px solid black;">Decline Partnership</th>
						</tr>
						<?php
							if (mysqli_num_rows($request_setup) == 0){
								echo '<tr>';									
									echo '<td colspan="10" style="border:1px solid black;">You have no Deal Requests!</td>';
								echo '</tr>';
							} else {
								while ($request = mysqli_fetch_array($request_setup)){
									$partner_id = $request['vendor_id'];
									$partner_setup = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `vendor_id` = '$partner_id'");
									$partner = mysqli_fetch_array($partner_setup);
									$fb_array = fbStuff($partner['facebook'],'1703713406526137','1bd51b8e55d973d8e0596a9dbc351bd8');
									$fb_like = $fb_array['0'];
									$fb_talk = $fb_array['1'];
									$fb_visit = $fb_array['2'];
									
									echo '<tr>';									
										echo '<td style="border:1px solid black;">'.$partner['company'].'</td>';
										echo '<td style="border:1px solid black;">'.$partner['username'].'</td>';
										echo '<td style="border:1px solid black;">'.getCategory($partner['sector']).'</td>';
										echo '<td style="border:1px solid black;">'.$fb_like.'</td>';
										echo '<td style="border:1px solid black;">'.$fb_talk.'</td>';
										echo '<td style="border:1px solid black;">'.$fb_visit.'</td>';
										echo '<td style="border:1px solid black;">#</td>';
										echo '<td style="border:1px solid black;"><a style="width:100%" class="btn btn-default" href="../profile.php?id='.$partner_id.'&t=v" target="_blank">Profile</td>';
										echo '<td style="border:1px solid black;"><a style="width:100%" class="btn btn-success" href="./includes/process_snp.php?d='.$request['id'].'&p=1">Accept</td></td>';
										echo '<td style="border:1px solid black;"><a style="width:100%" class="btn btn-danger" href="./includes/process_snp.php?d='.$request['id'].'&p=0">Decline</td></td>';
									echo '</tr>';
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