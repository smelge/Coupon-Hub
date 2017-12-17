<?php
	// Set up Sessions
	include_once('./includes/security.php');
	//Restricted page, check for eligibility and if editing for a vendor, if not send to index
	
	if ($_GET['t'] == 'v'){
		if (!isset($_SESSION['company'])){
			header('Location: ./'.$homelink);		
		} elseif ($_SESSION['company_id'] != $_GET['id']){
			header('Location: ./'.$homelink);
		}
	} else {
		
		if ($_GET['id'] != $_SESSION['user_id']){
			header('Location: ./'.$homelink);
		}	
		
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
			
			if (isset($_SESSION['company'])){
				$company_name = $_SESSION['company'];
				$company_set = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `company` = '$company_name'");
				$company = mysqli_fetch_array($company_set);
			}			
			
			$profile_set = mysqli_query($secure_db,"SELECT * FROM `members` WHERE `user_id` = '$user_id'");
			$profile = mysqli_fetch_array($profile_set);
		?>
	</head>
	<body class="container-fluid <?php echo $indexBg;?>">
		<!-- START CONTENT -->
		<?php include ('./includes/'.$indexNav.'.php');?>		
		<?php 
			if (isset($_SESSION['company'])){
				include('./includes/banner.php');
				echo '
				<div class="row topmargin">
					<div class="col-sm-12 heading">
						'.$company_name.' - Edit Profile Details
					</div>
				</div>
				';
				$type = 'vendor';
			} else {
				echo '
				<div class="row pad toprow">
					<div class="col-sm-12 heading">
						'.$_SESSION['username'].' - Edit Profile Details
					</div>
				</div>
				';
				$type = 'user';
			}
		?>
		
		<div class="row">			
			<form action="./includes/edit_profile_process.php?type=<?php echo $type;?>" method="POST" enctype="multipart/form-data">
				<?php
					if (isset($_SESSION['company'])){
						echo '<div class="col-sm-4 form-group">';
					} else {
						echo '<div class="col-sm-12 form-group">';
					}
				?>					
					<div class="standard">
						<div class="form-heading">
							User Profile
						</div>
						<label for="emailPersonal">Email address</label>
						<input type="email" class="form-control" name="emailPersonal" id="emailPersonal" placeholder="Not Available Yet" value="<?php echo $profile['email'];?>"/>
						
						<label for="oldPassword">Old Password</label>
						<input type="password" class="form-control" name="oldPassword" id="oldPassword" placeholder="Not Available Yet" disabled="disabled"/>
						<label for="newPassword">New Password</label>
						<input type="password" class="form-control" name="newPassword" id="newPassword" placeholder="Not Available Yet" disabled="disabled"/>
						<label for="repeatPassword">Repeat New Password</label>
						<input type="password" class="form-control" name="repeatPassword" id="repeatPassword" placeholder="Not Available Yet" disabled="disabled"/>
						
						<label for="forename">Forename</label>
						<input type="text" class="form-control" id="forename" name="forename" placeholder="Forename" value="<?php echo $profile['forename'];?>"/>
						<label for="surname">Surname</label>
						<input type="text" class="form-control" id="surname" name="surname" placeholder="Surname" value="<?php echo $profile['surname'];?>"/>
						<label for="gender">Gender</label>
						<select name="gender" class="form-control">						
							<option value="female" <?php if ($profile['gender'] == 'female'){echo 'selected';}?>>Female</option>
							<option value="male" <?php if ($profile['gender'] == 'male'){echo 'selected';}?>>Male</option>
							<option value="other" <?php if ($profile['gender'] == 'other'){echo 'selected';}?>>Other</option>
						</select>
						
						<?php
							$dob_full = explode("-",$profile['date_of_birth']);
							
							$dob_day = $dob_full[2];
							$dob_month = $dob_full[1];
							$dob_year = $dob_full[0];
						?>
						<label for="dob">Date of Birth</label></br>
						<div class="col-sm-4 padding">
							<select name="day" class="form-control">
								<?php
									for ($day = 1;$day <= 31;$day++){
										if ($day == 1 || $day == 21 || $day == 31){
											$suffix = 'st';
										} elseif ($day == 2 || $day == 22){
											$suffix = 'nd';
										} elseif ($day == 3 || $day == 23){
											$suffix = 'rd';
										} else {
											$suffix = 'th';
										}
										if($day == $dob_day){
											echo '<option value="'.$day.'" selected>'.$day.$suffix.'</option>';
										} else {
											echo '<option value="'.$day.'">'.$day.$suffix.'</option>';
										}
									}
								?>
							</select>
						</div>
						<div class="col-sm-4 padding">
							<select name="month" class="form-control">
								<option value="1" <?php if($dob_month == 1){echo 'selected';}?>>January</option>
								<option value="2" <?php if($dob_month == 2){echo 'selected';}?>>February</option>
								<option value="3" <?php if($dob_month == 3){echo 'selected';}?>>March</option>
								<option value="4" <?php if($dob_month == 4){echo 'selected';}?>>April</option>
								<option value="5" <?php if($dob_month == 5){echo 'selected';}?>>May</option>
								<option value="6" <?php if($dob_month == 6){echo 'selected';}?>>June</option>
								<option value="7" <?php if($dob_month == 7){echo 'selected';}?>>July</option>
								<option value="8" <?php if($dob_month == 8){echo 'selected';}?>>August</option>
								<option value="9" <?php if($dob_month == 9){echo 'selected';}?>>September</option>
								<option value="10" <?php if($dob_month == 10){echo 'selected';}?>>October</option>
								<option value="11" <?php if($dob_month == 11){echo 'selected';}?>>November</option>
								<option value="12" <?php if($dob_month == 12){echo 'selected';}?>>December</option>
							</select>
						</div>
						<div class="col-sm-4 padding">
							<select name="year" class="form-control">
								<?php
									for ($year = date("Y");$year >= 1900;$year--){
										if($dob_year == $year){
											echo '<option value="'.$year.'" selected>'.$year.'</option>';
										} else {
											echo '<option value="'.$year.'">'.$year.'</option>';
										}
									}
								?>
							</select>
						</div>
						
						<label for="housePersonal">House</label>
						<input type="text" class="form-control" name="housePersonal" id="housePersonal" placeholder="House" value="<?php echo $profile['house'];?>"/>
						<label for="streetPersonal">Street</label>
						<input type="text" class="form-control" name="streetPersonal" id="streetPersonal" placeholder="Street" value="<?php echo $profile['street'];?>"/>
						<label for="townPersonal">Town</label>
						<input type="text" class="form-control" name="townPersonal" id="townPersonal" placeholder="Town" value="<?php echo $profile['town'];?>"/>
						<label for="postcodePersonal">Postcode</label>
						<input type="text" class="form-control" name="postcodePersonal" id="postcodePersonal" placeholder="Postcode" value="<?php echo $profile['postcode'];?>"/>
						<label for="countryPersonal">Country</label>
						<select name="countryPersonal" name="countryPersonal" id="countryPersonal" class="form-control">
							<?php
								$country_array = array("United Kingdom","Afghanistan", "Aland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Barbuda", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Trty.", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Caicos Islands", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Futuna Islands", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard", "Herzegovina", "Holy See", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Jan Mayen Islands", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea", "Korea (Democratic)", "Kuwait", "Kyrgyzstan", "Lao", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "McDonald Islands", "Mexico", "Micronesia", "Miquelon", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "Nevis", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory, Occupied", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Principe", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Barthelemy", "Saint Helena", "Saint Kitts", "Saint Lucia", "Saint Martin (French part)", "Saint Pierre", "Saint Vincent", "Samoa", "San Marino", "Sao Tome", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia", "South Sandwich Islands", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "The Grenadines", "Timor-Leste", "Tobago", "Togo", "Tokelau", "Tonga", "Trinidad", "Tunisia", "Turkey", "Turkmenistan", "Turks Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "US Minor Outlying Islands", "Uzbekistan", "Vanuatu", "Vatican City State", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (US)", "Wallis", "Western Sahara", "Yemen", "Zambia", "Zimbabwe");
								foreach ($country_array as $country){
									if ($country == $profile['country']){
										echo '<option value="'.$country.'" selected>'.$country.'</option>';
									} else {
										echo '<option value="'.$country.'">'.$country.'</option>';
									}
									
								}
							?>
						</select>
						<!-- avatar -->
					</div>
				</div>
				<?php
					if (isset($_SESSION['company'])){
						echo '
						<div class="col-sm-8 padding">				
							<div class="col-sm-12 form-group">								
								<div class="standard">
									<div class="form-heading">
										Company Details
									</div>
									<label for="companyProfile">Company Profile</label>
									<textarea class="form-control" rows="9" name="companyProfile" id="companyProfile" placeholder="Company Profile">'.$company['company_profile'].'</textarea>
									<hr>
									<label for="banner">Update Banner (Max size: 1500px x 250px, 500kb)</label>
									<input type="file" id="image" name="banner-image"/>
									<!-- AJAX this? -->
									<hr>
									<label for="sector">Primary Business Sector</label>
									<select name="sector" id="sector" class="form-control">
						';
										$category_set = mysqli_query($dbcats,"SELECT *FROM `categories` ORDER BY `name` ASC");
										while ($category = mysqli_fetch_array($category_set)){
											if ($category['id'] == $company['sector']){
												echo '<option value="'.$category['id'].'" selected>'.$category['name'].'</option>';
											} else {
												echo '<option value="'.$category['id'].'">'.$category['name'].'</option>';
											}
											
										}
						echo '
									</select>
									<!-- opening hours -->
								</div>
							</div>					
							<div class="col-sm-12 form-group">
								<div class="profile-heading">
									Contacts & Details
								</div>
								<div class="standard">
									<label class="sr-only" for="facebook">Facebook</label>
									<div class="input-group">
										<div class="input-group-addon">www.facebook.com/</div>
										<input type="text" class="form-control" name="facebook" id="facebook" placeholder="Facebook Name" value="'.$company['facebook'].'"/>
									</div>
									<label class="sr-only" for="twitter">Twitter</label>
									<div class="input-group">
										<div class="input-group-addon">www.twitter.com/</div>
										<input type="text" class="form-control" name="twitter" id="twitter" placeholder="Twitter Name" value="'.$company['twitter'].'"/>
									</div>
									<label class="sr-only" for="linkedin">LinkedIn</label>
									<div class="input-group">
										<div class="input-group-addon">www.linkedin.com/</div>
										<input type="text" class="form-control" name="linkedin" id="linkedin" placeholder="LinkedIn User" value="'.$company['linkedin'].'" disabled="disabled"/>
									</div>
									
									<label for="emailBusiness">Business Email address</label>
									<input type="email" class="form-control" name="emailBusiness" id="emailBusiness" placeholder="Not Available Yet" disabled="disabled" value="'.$company['email'].'"/>
									
									<label class="sr-only" for="website">Website</label>
									<div class="input-group">
										<div class="input-group-addon">http://www.</div>
										<input type="text" class="form-control" name="website" id="website" placeholder="website.com" value="'.$company['website'].'"/>
									</div>
									<!--
									<div class="checkbox">
										<label>
											<input type="checkbox" name="showAddress" value="no" checked> Display Company address on Profile page?
										</label>
									</div>
									-->
									<label for="phoneBusiness">Telephone</label>
									<input type="text" class="form-control" name="phoneBusiness" id="phoneBusiness" placeholder="Phone Number" value="'.phoneFormat($company['telephone']).'"/>
									<label for="houseBusiness">Building</label>
									<input type="text" class="form-control" name="houseBusiness" id="houseBusiness" placeholder="Building" value="'.$company['house'].'"/>
									<label for="streetBusiness">Street</label>
									<input type="text" class="form-control" name="streetBusiness" id="streetBusiness" placeholder="Street" value="'.$company['street'].'"/>
									<label for="townBusiness">Town</label>
									<input type="text" class="form-control" name="townBusiness" id="townBusiness" placeholder="Town" value="'.$company['town'].'"/>
									<label for="postcodeBusiness">Postcode</label>
									<input type="text" class="form-control" name="postcodeBusiness" id="postcodeBusiness" placeholder="Postcode" value="'.$company['postcode'].'"/>
									<label for="countryBusiness">Country</label>
									<select name="countryBusiness" name="countryBusiness" id="countryBusiness" class="form-control">
						';
										$country_array = array("United Kingdom","Afghanistan", "Aland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Barbuda", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Trty.", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Caicos Islands", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Futuna Islands", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard", "Herzegovina", "Holy See", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Jan Mayen Islands", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea", "Korea (Democratic)", "Kuwait", "Kyrgyzstan", "Lao", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "McDonald Islands", "Mexico", "Micronesia", "Miquelon", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "Nevis", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory, Occupied", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Principe", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Barthelemy", "Saint Helena", "Saint Kitts", "Saint Lucia", "Saint Martin (French part)", "Saint Pierre", "Saint Vincent", "Samoa", "San Marino", "Sao Tome", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia", "South Sandwich Islands", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "The Grenadines", "Timor-Leste", "Tobago", "Togo", "Tokelau", "Tonga", "Trinidad", "Tunisia", "Turkey", "Turkmenistan", "Turks Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "US Minor Outlying Islands", "Uzbekistan", "Vanuatu", "Vatican City State", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (US)", "Wallis", "Western Sahara", "Yemen", "Zambia", "Zimbabwe");
										foreach ($country_array as $country){
											if ($country == $profile['country']){
												echo '<option value="'.$country.'" selected>'.$country.'</option>';
											} else {
												echo '<option value="'.$country.'">'.$country.'</option>';
											}
										}
						echo '
									</select>
									<!-- map co-ordinates -->
								</div>
							</div>
						</div>
						';
					}
				?>
				
				<div class="col-sm-12 btn-group standard">					
					<input class="btn btn-success btn-lg" type="submit" value="Submit"/>
					<input class="btn btn-danger btn-lg" type="reset" value="Reset Form"/>
				</div>
			</form>
		</div>
		<?php include ('./includes/footer.php');?>		
		<!-- END CONTENT -->
	</body>
</html>