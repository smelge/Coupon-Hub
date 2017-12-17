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
			
			$request_setup = mysqli_query($secure_db,"SELECT * FROM `social_network_partners` WHERE (`partner_id` = '$vendor_id' OR `vendor_id` = '$vendor_id')");
			
			//Get Facebook Likes Count of a page
			
			function fbStuff($id,$appid,$appsecret){
				//Construct a Facebook URL
				$json_url ='https://graph.facebook.com/'.$id.'?access_token='.$appid.'|'.$appsecret.'&fields=likes,talking_about_count,were_here_count';
						
				if (@file_get_contents($json_url) === false){
					$fb_likes = 'Error';
					$fb_talk = 'Error';
					$fb_visits = 'Error';
					$fb_state = 0;
				} else {
					$json = file_get_contents($json_url);
					$json_output = json_decode($json,true);					
					
					//Extract the likes count from the JSON object
					$fb_likes = $json_output['likes'];
					$fb_talk = $json_output['talking_about_count'];
					$fb_visits = $json_output['were_here_count'];
					$fb_state = 1;
				}
				
				return array($fb_likes,$fb_talk,$fb_visits);			
			}
			function twitterStuff($twitterId){
				// Get Follower number
				if ($twitterId == false){
					$result = 'No Twitter Added';
				} else {
					$data = file_get_contents('https://cdn.syndication.twimg.com/widgets/followbutton/info.json?screen_names='.$twitterId); 
					$parsed =  json_decode($data,true);
					$result = $parsed[0]['followers_count'];
				}
				return $result;				
			}
			
		?>		
	</head>
	<body class="container-fluid vendor-body">
		<!-- START CONTENT -->
		<?php include ('./includes/snp-header.php');?>		
		<!-- SNP Menu -->
		<div class="row">
			<div class="col-sm-12 vendor-nav btn-group topmargin bottommargin">
				<a class="btn btn-default active">Partnerships</a>
				<a class="btn btn-default" href="./request.php">View Requests</a>
				<a class="btn btn-default" href="./find_partner.php">Add Partner</a>
				<a class="btn btn-default" href="./snp_deal.php">Set up Deal</a>
				<a class="btn btn-default" href="./deal_request.php">Deal Requests</a>
				<a class="btn btn-default" href="./review_deals.php">Review Deals</a>
			</div>
		</div>
		
		<div class="row">
			<div class="col-sm-12">
				<div class="table-background">
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
							<th style="border:1px solid black;">Set Up a Deal</th>
						</tr>
						<?php
							if (mysqli_num_rows($request_setup) == 0){
								echo '<tr>';									
									echo '<td colspan="9" style="border:1px solid black;">You have no Partners yet!</td>';
								echo '</tr>';
							} else {
								while ($request = mysqli_fetch_array($request_setup)){
									if ($request['vendor_id'] == $vendor_id){
										$partner_id = $request['partner_id'];
									} else {
										$partner_id = $request['vendor_id'];
									}
									
									$partner_setup = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `vendor_id` = '$partner_id'");
									$partner = mysqli_fetch_array($partner_setup);
									$fb_array = fbStuff($partner['facebook'],'1703713406526137','1bd51b8e55d973d8e0596a9dbc351bd8');
									$fb_like = $fb_array['0'];
									$fb_talk = $fb_array['1'];
									$fb_visit = $fb_array['2'];
									
									echo '<tr>';
									if ($request['approved'] == 0){
										// Partnership pending
										echo '<tr class="warning">';
									} elseif ($request['approved'] == 2){
										// Partnership Declined
										echo '<tr class="danger">';
									} else {
										// Partnership approved
										echo '<tr>';
									}
										echo '<td style="border:1px solid black;">'.$partner['company'].'</td>';
										echo '<td style="border:1px solid black;">'.$partner['username'].'</td>';
										echo '<td style="border:1px solid black;">'.getCategory($partner['sector']).'</td>';
										echo '<td style="border:1px solid black;">'.$fb_like.'</td>';
										echo '<td style="border:1px solid black;">'.$fb_talk.'</td>';
										echo '<td style="border:1px solid black;">'.$fb_visit.'</td>';
										echo '<td style="border:1px solid black;">'.twitterStuff($partner['twitter']).'</td>';
										echo '<td style="border:1px solid black;"><a style="width:100%" class="btn btn-default" href="../profile.php?id='.$partner_id.'&t=v" target="_blank">Profile</td>';
										if ($fb_like == 'Error'){
											echo '<td style="border:1px solid black;">No Social Media</td>';
										} else {
											if ($request['approved'] == 0){
												// Partnership pending
												echo '<td style="border:1px solid black;">Partnership Request Pending</td>';
											} elseif ($request['approved'] == 2){
												// Partnership Declined
												echo '<td style="border:1px solid black;">Partnership Declined</td>';
											} else {
												// Partnership approved
												echo '<td style="border:1px solid black;"><a style="width:100%" class="btn btn-success" href="./snp_deal.php">Make a Deal</a></td>';
											}
											
										}
										
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