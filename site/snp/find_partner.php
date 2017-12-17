<?php
	// Set up Sessions
	include_once('./includes/snp_security.php');
	//Restricted page, check for eligibility, if not send to index
	if (!isset($_SESSION['company'])){
		header('Location: ../'.$homelink);		
	}
	
	$pageTitle = 'The Coupon Hub';
	$metaKeywords = '';
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php
			include_once('./includes/meta.php');			
			include_once('./includes/scripts.php');
		
			$page = 'Find and Add a new Social Network Partner';
		
			$company_name = $_SESSION['company'];
			$company_set = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `company` = '$company_name'");
			$company = mysqli_fetch_array($company_set);
			$vendor_id = $company['vendor_id'];
			
			if (isset($_POST['search'])){
				$search_string = mysqli_real_escape_string($secure_db,$_POST['search']);
			}			
			
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
			
			function twitterStuff($twitterId){
				// Get Follower number
				$data = file_get_contents('https://cdn.syndication.twimg.com/widgets/followbutton/info.json?screen_names='.$twitterId); 
				$parsed =  json_decode($data,true);
				return $parsed[0]['followers_count'];
			}
				
			$my_company_id = $_SESSION['company_id'];
		?>
	</head>
	<body class="container-fluid vendor-body">
		<!-- START CONTENT -->
		<?php 
			include ('./includes/snp-header.php');
		?>
		<!-- SNP Menu -->
		<div class="row">
			<div class="col-sm-12 vendor-nav btn-group topmargin bottommargin">
				<a class="btn btn-default" href="./partnerships.php">Partnerships</a>
				<a class="btn btn-default" href="./request.php">View Requests</a>
				<a class="btn btn-default active">Add Partner</a>
				<a class="btn btn-default" href="./snp_deal.php">Set up Deal</a>
				<a class="btn btn-default" href="./deal_request.php">Deal Requests</a>
				<a class="btn btn-default" href="./review_deals.php">Review Deals</a>
			</div>
		</div>
		
		<div class="row pad">
			<div class="alert alert-info" role="alert">
				Companies need to have a linked Facebook page to qualify for a Social Media Partnership. Those without will not be displayed in the results.
			</div>
		</div>
		<div class="row equal">			
			<div class="col-sm-6">
				<div class="profile-heading">
					Find a Partner
				</div>
				<div class="standard">
					<?php						
						$company_setup = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `vendor_id` != '$vendor_id' AND `facebook` != '' ORDER BY `company` ASC");
						echo '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';
							while ($company = mysqli_fetch_array($company_setup)){
								// List of all vendors
								$current_vendor_check = $company['vendor_id'];
								$company_partner_setup = mysqli_query($secure_db,"SELECT * FROM `social_network_partners` WHERE `vendor_id` = '$current_vendor_check' OR `partner_id` = '$current_vendor_check'");
								$company_partner = mysqli_fetch_array($company_partner_setup);
								
								if ($company['banner'] == false){
									$company_banner = 'default.jpg';
								} else {
									$company_banner = $company['banner'];
								}
								echo '<div class="panel panel-default">';
									echo '<div class="panel-heading" role="tab" id="heading'.$company['vendor_id'].'">';
										echo '<h4 class="panel-title">';
											echo '<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse'.$company['vendor_id'].'" aria-expanded="false" aria-controls="collapse'.$company['vendor_id'].'">';
												echo $company['company'];
											echo '</a>';
										echo '</h4>';
									echo '</div>';
									echo '<div id="collapse'.$company['vendor_id'].'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading'.$company['vendor_id'].'">';
										echo '<div class="panel-body">';
											echo '<img class=" img-responsive" src="../assets/vendor-banners/'.$company_banner.'" alt="'.$company['company'].'"/>';
											if ($company['company_profile'] == false){
												echo 'No Profile Set';
											} else {
												echo html_entity_decode($company['company_profile']);
											}										
											echo '<hr>';
											
											$fb_array = fbStuff($company['facebook'],'1703713406526137','1bd51b8e55d973d8e0596a9dbc351bd8');
											$fb_like = $fb_array['0'];
											$fb_talk = $fb_array['1'];
											$fb_visit = $fb_array['2'];
											
											echo '<table class="table table-bordered table-hover table-responsive" style="border:2px solid black;">';
												echo '<tr style="border:1px solid black;">';
													echo '<th style="border:1px solid black;" colspan="3">Facebook</th>';
												echo '</tr>';
												echo '<tr>';
													echo '<td style="border:1px solid black;">'.$fb_like.' Likes</td>';
													echo '<td style="border:1px solid black;">'.$fb_talk.' Talking About</td>';
													echo '<td style="border:1px solid black;">'.$fb_visit.' Visits</td>';
												echo '</tr>';
												echo '<tr style="border:1px solid black;">';
													echo '<th style="border:1px solid black;" colspan="3">Twitter</th>';
												echo '</tr>';	
												echo '<tr colspan="2">';
													if ($company['twitter'] == true){																								
														echo '<td style="border:1px solid black;" colspan="3">'.twitterStuff($company['twitter']).' Followers</td>';
													} else {
														echo '<td style="border:1px solid black;" colspan="3">No twitter Assigned</td>';
													}
												echo '</tr>';																							
											echo '</table>';
											echo '<table class="table table-bordered table-hover table-responsive" style="border:2px solid black;">';
												echo '<tr style="border:1px solid black;">';
													echo '<th style="border:1px solid black;" colspan="3">Other Stuff</th>';
												echo '</tr>';
												echo '<tr>';
													echo '<td style="border:1px solid black;">Primary Category: '.getCategory($company['sector']).'</td>';
													$partner_id = $company['vendor_id'];
													$setup_find_partner = mysqli_query($secure_db,"SELECT * FROM `social_network_partners` WHERE `vendor_id` = '$partner_id'");
													echo '<td style="border:1px solid black;">'.mysqli_num_rows($setup_find_partner).' Social Network Partners</td>';
												echo '</tr>';
											echo '</table>';
											echo '<hr>';
											echo '<div style="text-align:center;">';
												echo '<div class="btn-group">';
													if ($company_partner['vendor_id'] == $my_company_id || $company_partner['vendor_id'] == $current_vendor_check){
														if ($company_partner['approved'] == 1){
															// Already partnered with this company, block Request button
															echo '<a class="btn btn-default" href="../profile.php?id='.$company['vendor_id'].'&t=v">'.$company['company'].'\'s Profile</a>';
															echo '<a class="btn btn-success" disabled="disabled" href="#">Already a Partner</a>';
														} else {
															// Awaiting confirmation of partnership, block Request button
															echo '<a class="btn btn-default" href="../profile.php?id='.$company['vendor_id'].'&t=v">'.$company['company'].'\'s Profile</a>';
															echo '<a class="btn btn-warning" disabled="disabled" href="#">Awaiting Partnership Response</a>';
														}									
													} else {
														echo '<a class="btn btn-default" href="../profile.php?id='.$company['vendor_id'].'&t=v">'.$company['company'].'\'s Profile</a>';
														echo '<a class="btn btn-warning" href="./partner_request.php?vendor='.$company['vendor_id'].'">Request Partnership</a>';
													}
												echo '</div>';
											echo '</div>';
										echo '</div>';
									echo '</div>';
								echo '</div>';
							}
						echo '</div>';
					?>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="profile-heading">
					Search for a Partner
				</div>
				<div class="standard">
					<form class="form-horizontal" action="./find_partner.php" method="post">
						<div class="input-group">						
							<input class="form-control" type="text" id="search" name="search" placeholder="Search for name or keyword" <?php if (isset($search_string) && $search_string != ''){echo 'value="'.$search_string.'"';}?>/>
							<span class="input-group-btn">
								<input type="submit" class="btn btn-success" value="Search!"/>
							</span>
						</div>
					</form>
					<?php
						if (isset($search_string)){
							if ($search_string != ''){						
								$search_term_setup = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `company` LIKE '%$search_string%' AND `vendor_id` != '$vendor_id' AND `facebook` != '' ORDER BY `company` ASC");
								$search_num = mysqli_num_rows($search_term_setup);
								if ($search_num > 0){
									echo '<hr>'.$search_num.' Results found<hr>';
									
									echo '<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">';
										while ($search_result = mysqli_fetch_array($search_term_setup)){
											$search_current_vendor_check = $search_result['vendor_id'];
											$search_company_partner_setup = mysqli_query($secure_db,"SELECT * FROM `social_network_partners` WHERE `vendor_id` = '$search_current_vendor_check' OR `partner_id` = '$search_current_vendor_check'");
											$search_company_partner = mysqli_fetch_array($search_company_partner_setup);
								
											if ($search_result['banner'] == false){
												$search_result_banner = 'default.jpg';
											} else {
												$search_result_banner = $search_result['banner'];
											}
											
											echo '<div class="panel panel-default">';
												echo '<div class="panel-heading" role="tab" id="search-heading'.$search_result['vendor_id'].'">';
													echo '<h4 class="panel-title">';
														echo '<a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#search-collapse'.$search_result['vendor_id'].'" aria-expanded="false" aria-controls="search-collapse'.$search_result['vendor_id'].'">';
															echo $search_result['company'];
														echo '</a>';
													echo '</h4>';
												echo '</div>';
												echo '<div id="search-collapse'.$search_result['vendor_id'].'" class="panel-collapse collapse" role="tabpanel" aria-labelledby="search-heading'.$search_result['vendor_id'].'">';
													echo '<div class="panel-body">';
														echo '<img class=" img-responsive" src="../assets/vendor-banners/'.$search_result_banner.'" alt="'.$search_result['company'].'"/>';
														if ($search_result['company_profile'] == false){
															echo 'No Profile Set';
														} else {
															echo html_entity_decode($search_result['company_profile']);
														}										
														echo '<hr>';
														
														$fb_array = fbStuff($search_result['facebook'],'1703713406526137','1bd51b8e55d973d8e0596a9dbc351bd8');
														$fb_like = $fb_array['0'];
														$fb_talk = $fb_array['1'];
														$fb_visit = $fb_array['2'];
														
														echo '<table class="table table-bordered table-hover table-responsive" style="border:2px solid black;">';
															echo '<tr style="border:1px solid black;">';
																echo '<th style="border:1px solid black;" colspan="3">Facebook</th>';
															echo '</tr>';
															echo '<tr>';
																echo '<td style="border:1px solid black;">'.$fb_like.' Likes</td>';
																echo '<td style="border:1px solid black;">'.$fb_talk.' Talking About</td>';
																echo '<td style="border:1px solid black;">'.$fb_visit.' Visits</td>';
															echo '</tr>';
															echo '<tr style="border:1px solid black;">';
																echo '<th style="border:1px solid black;" colspan="3">Twitter</th>';
															echo '</tr>';
															echo '<tr>';
																echo '<td style="border:1px solid black;"># Likes</td>';
																echo '<td style="border:1px solid black;"># Talking About</td>';
																echo '<td style="border:1px solid black;"># Visits</td>';
															echo '</tr>';											
														echo '</table>';
														echo '<table class="table table-bordered table-hover table-responsive" style="border:2px solid black;">';
															echo '<tr style="border:1px solid black;">';
																echo '<th style="border:1px solid black;" colspan="3">Other Stuff</th>';
															echo '</tr>';
															echo '<tr>';
																echo '<td style="border:1px solid black;">Primary Category: '.getCategory($search_result['sector']).'</td>';
																echo '<td style="border:1px solid black;"># Social Network Partners</td>';
															echo '</tr>';
														echo '</table>';
														echo '<hr>';
														echo '<div style="text-align:center;">';
															echo '<div class="btn-group">';
																if ($search_company_partner['vendor_id'] == $my_company_id || $search_company_partner['vendor_id'] == $search_current_vendor_check){
																	if ($search_company_partner['approved'] == 1){
																		// Already partnered with this company, block Request button
																		echo '<a class="btn btn-default" href="../profile.php?id='.$search_result['vendor_id'].'&t=v">'.$search_result['company'].'\'s Profile</a>';
																		echo '<a class="btn btn-success" disabled="disabled" href="#">Already a Partner</a>';
																	} else {
																		// Awaiting confirmation of partnership, block Request button
																		echo '<a class="btn btn-default" href="../profile.php?id='.$search_result['vendor_id'].'&t=v">'.$search_result['company'].'\'s Profile</a>';
																		echo '<a class="btn btn-warning" disabled="disabled" href="#">Awaiting Partnership Response</a>';
																	}									
																} else {
																	echo '<a class="btn btn-default" href="../profile.php?id='.$search_result['vendor_id'].'&t=v">'.$search_result['company'].'\'s Profile</a>';
																	echo '<a class="btn btn-warning" href="./partner_request.php?vendor='.$search_result['vendor_id'].'">Request Partnership</a>';
																}
															echo '</div>';
														echo '</div>';
													echo '</div>';
												echo '</div>';
											echo '</div>';
										}
									echo '</div>';
									
									
								} else {
									echo '<hr>No Matches Found';
								}
							} else {
								echo '<hr>No Search Terms Entered';
							}
						}
					?>
				</div>
			</div>
		</div>
		<?php include ('../includes/footer.php');?>		
		<!-- END CONTENT -->
	</body>
</html>