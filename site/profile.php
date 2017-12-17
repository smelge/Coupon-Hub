<?php
	include_once('./includes/security.php');
	// Set up Sessions
	$pageTitle = 'The Coupon Hub';
	$metaKeywords = '';
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php
			include_once('./includes/meta.php');			
			include_once('./includes/scripts.php');			

			$profile_id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_SPECIAL_CHARS);
			$type = filter_input(INPUT_GET, 't', FILTER_SANITIZE_SPECIAL_CHARS);
			
			if ($type == 'v'){
				if (isset($_SESSION['company_id'])){
					$my_vendor_id = $_SESSION['company_id'];
				}				
				$profile_set = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `vendor_id` = '$profile_id'");
				$profile = mysqli_fetch_array($profile_set);
				$registered_user = $profile['username'];
				$mailto_setup = mysqli_query($secure_db,"SELECT * FROM `members` WHERE `username` = '$registered_user'");
				$mailto_user = mysqli_fetch_array($mailto_setup);
				$mailto = $registered_user;
				$mail_id = $mailto_user['user_id'];
			} elseif ($type == 'u'){
				$profile_set = mysqli_query($secure_db,"SELECT * FROM `members` WHERE `user_id` = '$profile_id'");
				$profile = mysqli_fetch_array($profile_set);
				$mailto = $profile['username'];
				$mail_id = $profile['user_id'];
			} else {
				header('Location: ./'.$homelink);
			}
		?>
		<div class="modal fade" id="private-message" tabindex="-1" role="dialog" aria-labelledby="private-messageLabel">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title" id="private-messageLabel">Private Message to <?php echo $mailto;?></h4>
					</div>
					<div class="modal-body pad">
						<form class="form-horizontal pad" action="./includes/send.php" method="POST" enctype="multipart/form-data">
							<input type="hidden" name="user_id" value="<?php echo $user_id;?>"/>
							<input type="hidden" name="victim_id" value="<?php echo $mail_id;?>"/>
							<div class="form-group">
								<label for="title">Title</label>
								<input class="form-control" id="title" required name="title" type="text"/>
							</div>
							<div class="form-group">
								<label for="reply">Reply</label>
								<textarea class="form-control" id="reply" name="reply"></textarea>
							</div>
							<div class="form-group">
								<input class="btn btn-success" type="submit" value="Send"/>
							</div>
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</head>
	<body class="container-fluid <?php echo $indexBg;?>">
		<!-- START CONTENT -->
		<?php include ('./includes/'.$indexNav.'.php');?>	
		<div class="row toprow">
			<div class="col-sm-3 pad">
				<div class="col-sm-12 catbar">
					<?php include ('./includes/categories.php');?>
				</div>
			</div>
			<div class="col-sm-9" style="padding:0 10px;">
				<?php
					switch ($type){
						case 'u':							
							echo '<div class="standard">';
								echo '<div class="form-heading">';
									echo $profile['username'].'\'s User Profile';
								echo '</div>';
								echo 'Display Name: ';
								if ($profile['display_name'] == true){
									echo $profile['display_name'];
								} else {
									echo $profile['username'];
								}										
								echo '</br>';
								$reg_date = explode(" ",$profile['registration_date']);
								echo 'Registered Since: '.$reg_date[0].'</br>';
							echo '</div>';
							if ($profile_id == $_SESSION['user_id']){								
								echo '<div class="standard topmargin">';
									echo '<div class="form-heading" style="margin-top:10px;">';
										echo 'Personal Profile</br>';
									echo '</div>';
									echo 'Email: '.$profile['email'].'</br>';
									echo 'Name: '.$profile['forename'].' '.$profile['surname'].'</br>';
									echo 'Gender: '.$profile['gender'].'</br>';
									echo 'Date of Birth: '.$profile['date_of_birth'].'</br>';
									echo 'Address:</br>';
									echo $profile['house'].'</br>'.$profile['street'].'</br>'.$profile['town'].'</br>'.$profile['postcode'].'</br>'.$profile['country'].'</br>';
									if ($profile['company'] == true){
										echo 'Works for: '.$profile['company'].'</br>';
									}
								echo '</div>';
							}
							break;
						case 'v':
							if ($profile['banner'] == true){
								echo '<img src="../assets/vendor-banners/'.$profile['banner'].'" class="img-responsive center-block" alt="'.$profile['company'].'"/>';
							} else {
								echo '<span class="banner-overlay">'.$profile['company'].'</span>';
								echo '<img src="../assets/vendor-banners/default.jpg" class="img-responsive" alt="'.$profile['company'].'"/>';					
							}							
							if ($profile['company_profile'] == true){
								echo '<div class="standard topmargin">';
									echo '<div class="form-heading" style="margin-top:10px;">';
										echo $profile['company'];
									echo '</div>';
									echo html_entity_decode($profile['company_profile']);
								echo '</div>';
							}							
							echo '<div class="standard padding topmargin" style="margin-bottom:10px;">';
								echo '<div class="form-heading" style="margin-top:10px;">';
									echo 'Network Partners';
								echo '</div>';
								$partner_setup = mysqli_query($secure_db,"SELECT * FROM `social_network_partners` WHERE `vendor_id` = '$profile_id' OR `partner_id` = '$profile_id' ORDER BY `id` ASC");
								$partner_number = mysqli_num_rows($partner_setup);								
								if ($partner_number == 0){
									echo 'No Current Network Partners';
								} else {
									while ($partner = mysqli_fetch_array($partner_setup)){									
										if ($partner['vendor_id'] == $profile_id){
											$partner_id = $partner['partner_id'];											
										} else {
											$partner_id = $partner['vendor_id'];											
										}
										$setup_find_partner = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `vendor_id` = '$partner_id'");
										$find_partner = mysqli_fetch_array($setup_find_partner);
										echo '<div class="row"><a href="./profile.php?id='.$find_partner['vendor_id'].'&t=v"><div class="col-sm-12 partners">'.$find_partner['company'].'</div></a></div>';										
									}									
								}
							echo '</div>';
							echo '<div class="standard topmargin" style="text-align:center;">';
								echo '<div class="form-heading" style="margin-top:10px;">';
									echo 'Contact';
								echo '</div>';
								echo '<a class="icon" style="font-size:66px;" href="#" data-toggle="modal" data-target="#private-message" title ="Send a PM"><i class="fa fa-envelope-o"></i></a>';
								
								if ($profile['facebook'] == true){echo '<a class="icon" style="font-size:60px;" href="https://www.facebook.com/'.$profile['facebook'].'" target="_blank" title ="Facebook"><i class="fa fa-facebook-square"></i></a>';}
								if ($profile['linkedin'] == true){echo '<a class="icon" style="font-size:60px;" href="https://www.linkedin.com/profile/view?id='.$profile['linkedin'].'" target="_blank" title ="LinkedIn"><i class="fa fa-linkedin-square"></i></a>';}
								if ($profile['twitter'] == true){echo '<a class="icon" style="font-size:60px;" href="https://twitter.com/'.$profile['twitter'].'" target="_blank" title ="Twitter"><i class="fa fa-twitter-square"></i></a>';}
								if ($profile['website'] == true){echo '<a class="icon" style="font-size:60px;" href="//'.$profile['website'].'" target="_blank" title ="Website"><i class="fa fa-globe"></i></a>';}
								if (isset($_SESSION['company'])){
									echo '<a class="icon" style="font-size:60px;" href="#" title ="Social Media Partnership"><i class="fa fa-plus-square"></i></a>';
								}
								//echo '<div class="fb-like" data-share="true" data-width="450" data-show-faces="true"></div>';
							echo '</div>';
							if (isset($_SESSION['company_id']) && $profile_id == $_SESSION['company_id']){								
								echo '<div class="standard topmargin">';
									echo '<div class="form-heading" style="margin-top:10px;">';
										echo 'Private Information';
									echo '</div>';
									echo 'Associated User: '.$profile['username'].'</br>';
									$reg_date = explode(" ",$profile['registration_date']);
									echo 'Registered Since: '.dateEdit($reg_date[0]).'</br>';
								echo '</div>';		
							}				
							break;	
					}
				?>
			</div>
		</div>
		<?php include ('./includes/footer.php');?>		
		<!-- END CONTENT -->
	</body>
</html>