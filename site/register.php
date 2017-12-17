<?php
	include_once('./includes/security.php');
	// Set up Sessions
	$pageTitle = 'The Coupon Hub Registration';
	$metaKeywords = '';
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php
			include_once('./includes/meta.php');			
			include_once('./includes/scripts.php');			
		?>
		<script type="text/javascript">
			$(document).ready(function() {
				var x_timer;    
				$("#username").keyup(function (e){
					clearTimeout(x_timer);
					var user_name = $(this).val();
					x_timer = setTimeout(function(){
						check_username_ajax(user_name);
					}, 1000);
				});

			function check_username_ajax(username){
				$("#user-result").html('<i class="fa fa-spin fa-cog"></i>');
				$.post('./includes/username_checker.php', {'username':username}, function(data) {
				  $("#user-result").html(data);
				});
			}
			});
		</script>
		<script type="text/javascript">
			$(document).ready(function() {
				var email_timer;    
				$("#email").keyup(function (e){
					clearTimeout(email_timer);
					var email = $(this).val();
					email_timer = setTimeout(function(){
						check_email_ajax(email);
					}, 1000);
				});

			function check_email_ajax(email){
				$("#email-result").html('<i class="fa fa-spin fa-cog"></i>');
				$.post('./includes/email_checker.php', {'email':email}, function(data) {
				  $("#email-result").html(data);
				});
			}
			});
		</script>
	</head>
	<body class="container-fluid <?php echo $indexBg;?>">
		<!-- START CONTENT -->
		<?php include ('./includes/'.$indexNav.'.php');?>	
		
		<?php
			if (isset($_SESSION['username'])){
				// Logged In
				echo "Sorry, you are already registered";
			} else {
				switch ($_GET['type']){
					case 'user':?>
						<div class="row pad toprow">
							<div class="col-sm-12 heading">
								User Registration
							</div>
						</div>
						<form class="form-horizontal" action="./includes/register_user.php" method="POST">
							<div class="row">
								<div class="col-sm-6" style="padding-right:5px;">
									<div class="col-sm-12 standard pad">
										<div class="col-sm-12">
											<div class="form-heading">
												About You
											</div>
											<div class="form-group">
												<label for="forename">Forename *</label>
												<input class="form-control" id="forename" required name="forename" type="text">
											</div>
											<div class="form-group">
												<label for="surname">Surname *</label>
												<input placeholder="" class="form-control" id="surname" name="surname" required type="text">
											</div>
											<div class="form-group">
												<label for="email">E-mail *</label>
												<input placeholder="email@domain.com" class="form-control" id="email" name="email" required type="text"><span id="email-result"></span>
											</div>
											<div class="form-group">
												<label for="password">Password *</label>
												<input placeholder="For extra security, use upper and lowercase characters, numbers and symbols" class="form-control" name="password" id="password" required type="password">
											</div>
											<div class="form-group">
												<label for="repeatpassword">Repeat Password *</label>
												<input placeholder="Type your password again" class="form-control" name="repeatpassword" id="repeatpassword" required type="password">
											</div>
											<div class="form-group">
												<label for="username">Username *</label>
												<input class="form-control" maxlength="15" name="username" id="username" required type="text"><span id="user-result"></span>
											</div>
											<div class="form-group">
												<label for="gender">Gender</label>
												<select name="gender" id="gender" class="form-control">
													<option value="female">Female</option>
													<option value="male">Male</option>
													<option value="other">Other</option>
												</select>
											</div>
											<div class="form-group">
												<label for="dob">Date of Birth</label></br>
												<div class="col-sm-4 padding">
													<select name="day" id="day" class="form-control">
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
																echo '<option value="'.$day.'">'.$day.$suffix.'</option>';
															}
														?>
													</select>
												</div>
												<div class="col-sm-4 padding">
													<select name="month" id="month" class="form-control">
														<option value="1">January</option>
														<option value="2">February</option>
														<option value="3">March</option>
														<option value="4">April</option>
														<option value="5">May</option>
														<option value="6">June</option>
														<option value="7">July</option>
														<option value="8">August</option>
														<option value="9">September</option>
														<option value="10">October</option>
														<option value="11">November</option>
														<option value="12">December</option>
													</select>
												</div>
												<div class="col-sm-4 padding">
													<select name="year" id="year" class="form-control">
														<?php
															for ($year = date("Y");$year >= 1900;$year--){
																echo '<option value="'.$year.'">'.$year.'</option>';
															}
														?>
													</select>
												</div>
											</div>
										</div>
									</div>									
								</div>
								<div class="col-sm-6" style="padding-left:5px;">
									<div class="col-sm-12 standard" style=" margin-bottom:10px;">
										<div class="col-sm-12">
											<div class="form-heading">
												Contact Details <span></span>
											</div>
											<div class="form-group">
												<label for="address1">House / Number</label>
												<input class="form-control" id="address1" name="address1" type="text">
											</div>
											<div class="form-group">
												<label for="address2">Street</label>
												<input class="form-control" id="address2" name="address2" type="text">
											</div>
											<div class="form-group">
												<label for="address3">Town/City</label>
												<input class="form-control" id="address3" name="address3" type="text">
											</div>
											<div class="form-group">
												<label for="address4">Postcode / Zipcode</label>
												<input class="form-control" id="address4" name="address4" type="text">
											</div>
											<div class="form-group">
												<label for="address5">Country *</label>
												<select name="address5" id="address5" class="form-control">
													<?php
														$country_array = array("United Kingdom","Afghanistan", "Aland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Barbuda", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Trty.", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Caicos Islands", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Futuna Islands", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard", "Herzegovina", "Holy See", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Jan Mayen Islands", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea", "Korea (Democratic)", "Kuwait", "Kyrgyzstan", "Lao", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "McDonald Islands", "Mexico", "Micronesia", "Miquelon", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "Nevis", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory, Occupied", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Principe", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Barthelemy", "Saint Helena", "Saint Kitts", "Saint Lucia", "Saint Martin (French part)", "Saint Pierre", "Saint Vincent", "Samoa", "San Marino", "Sao Tome", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia", "South Sandwich Islands", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "The Grenadines", "Timor-Leste", "Tobago", "Togo", "Tokelau", "Tonga", "Trinidad", "Tunisia", "Turkey", "Turkmenistan", "Turks Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "US Minor Outlying Islands", "Uzbekistan", "Vanuatu", "Vatican City State", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (US)", "Wallis", "Western Sahara", "Yemen", "Zambia", "Zimbabwe");
														foreach ($country_array as $country){
															echo '<option value="'.$country.'">'.$country.'</option>';
														}
													?>
												</select>
											</div>
										</div>
									</div>
									<div class="col-sm-12 standard" style="height:212px;">
										<div class="form-heading">
											Terms & Conditions
										</div>
									</div>
								</div>
							</div>
							<div class="row pad" style="margin-top:10px;">
								<div class="col-sm-12 standard" style="text-align:center;">
									<input type="checkbox" name="termscheck" id="termscheck" value="accepted" required/> I agree to terms & Conditions etc									
								</div>
							</div>
							<div class="row pad" style="margin-top:10px;">
								<div class="col-sm-12 standard" style="text-align:center;">
									<input class="btn btn-success" id="submit_button" style="margin:0 30px 0 0;" type="submit" value="Register"/>
									<input class="btn btn-danger" type="reset" value="Reset Form"/>
								</div>
							</div>
						</form><?php
						break;
					case 'business':
					?>
						<div class="row toprow">
							<div class="col-sm-12 heading">
								Company Registration
							</div>
						</div>
						<form class="form-horizontal" action="./includes/register_vendor.php" method="POST">
							<div class="row">
								<div class="col-sm-6" style="padding-right:5px;">
									<div class="col-sm-12 standard pad">
										<div class="col-sm-12">
											<div class="form-heading">
												User Details
											</div>
											<div class="form-group">
												<label for="forename">Forename *</label>
												<input class="form-control" id="forename" required name="forename" type="text">
											</div>
											<div class="form-group">
												<label for="surname">Surname *</label>
												<input placeholder="" class="form-control" id="surname" name="surname" required type="text">
											</div>
											<div class="form-group">
												<label for="email">E-mail *</label>
												<input placeholder="email@domain.com" class="form-control" id="email" name="email" required type="text"><span id="email-result"></span>
											</div>
											<div class="form-group">
												<label for="password">Password *</label>
												<input placeholder="For extra security, use upper and lowercase characters, numbers and symbols" class="form-control" name="password" id="password" required type="password">
											</div>
											<div class="form-group">
												<label for="repeatpassword">Repeat Password *</label>
												<input placeholder="Type your password again" class="form-control" name="repeatpassword" id="repeatpassword" required type="password">
											</div>
											<div class="form-group">
												<label for="username">Username *</label>
												<input class="form-control" maxlength="15" name="username" id="username" required type="text"><span id="user-result"></span>
											</div>
										</div>
									</div>
									<div class="col-sm-12 standard" style=" margin-top:10px;">
										<div class="col-sm-12">
											<div class="form-heading">
												Company Information
											</div>
											<div class="form-group">
												<label for="company">Company Name *</label>
												<input class="form-control" id="company" name="company" required type="text">
											</div>
											<div class="form-group">
												<label for="profile">Company Profile *</label>
												<textarea class="form-control" id="profile" name="profile" type="text"></textarea>
											</div>											
											<div class="form-group">
												<label for="facebook">Facebook</label>
												<input class="form-control" id="facebook" name="facebook" type="text">
											</div>
										</div>
									</div>								
								</div>
								<div class="col-sm-6" style="padding-left:5px;">
									<div class="col-sm-12 standard pad" style="margin-bottom:10px;">
										<div class="col-sm-12">		
											<div class="form-heading">
												Company Details
											</div>
											<div class="form-group">
												<label for="businessPhone">Telephone</label>
												<input class="form-control" id="businessPhone" name="businessPhone" type="text">
											</div>
											<div class="form-group">
												<label for="address1">Building / Number *</label>
												<input class="form-control" id="address1" name="address1" required type="text">
											</div>
											<div class="form-group">
												<label for="address2">Street *</label>
												<input class="form-control" id="address2" name="address2" required type="text">
											</div>
											<div class="form-group">
												<label for="address3">Town/City *</label>
												<input class="form-control" id="address3" name="address3" required type="text">
											</div>
											<div class="form-group">
												<label for="address4">Postcode / Zipcode *</label>
												<input class="form-control" id="address4" name="address4" required type="text">
											</div>
											<div class="form-group">
												<label for="address5">Country *</label>
												<select name="address5" id="address5" class="form-control">
													<?php
														$country_array = array("United Kingdom","Afghanistan", "Aland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Barbuda", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Trty.", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Caicos Islands", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Futuna Islands", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard", "Herzegovina", "Holy See", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Jan Mayen Islands", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea", "Korea (Democratic)", "Kuwait", "Kyrgyzstan", "Lao", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "McDonald Islands", "Mexico", "Micronesia", "Miquelon", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "Nevis", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory, Occupied", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Principe", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Barthelemy", "Saint Helena", "Saint Kitts", "Saint Lucia", "Saint Martin (French part)", "Saint Pierre", "Saint Vincent", "Samoa", "San Marino", "Sao Tome", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia", "South Sandwich Islands", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "The Grenadines", "Timor-Leste", "Tobago", "Togo", "Tokelau", "Tonga", "Trinidad", "Tunisia", "Turkey", "Turkmenistan", "Turks Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "US Minor Outlying Islands", "Uzbekistan", "Vanuatu", "Vatican City State", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (US)", "Wallis", "Western Sahara", "Yemen", "Zambia", "Zimbabwe");
														foreach ($country_array as $country){
															echo '<option value="'.$country.'">'.$country.'</option>';
														}
													?>
												</select>
											</div>
											<div class="form-group">
												<label for="website">Website</label>
												<input class="form-control" id="website" name="website" type="text">
											</div>
											<div class="form-group">
												<label for="sector">Primary Business Sector *</label>
												<select name="sector" id="sector" class="form-control">
													<?php
														$category_set = mysqli_query($dbcats,"SELECT *FROM `categories` ORDER BY `name` ASC");
														while ($category = mysqli_fetch_array($category_set)){
															echo '<option value="'.$category['id'].'">'.$category['name'].'</option>';
														}
													?>
												</select>
											</div>
										</div>
									</div>
									<div class="col-sm-12 standard" style="height:255px;">
										<div class="form-heading">
											Terms & Conditions
										</div>
									</div>
								</div>
							</div>
							<div class="row pad" style="margin-top:10px;">
								<div class="col-sm-12 standard" style="text-align:center;">
									<input type="checkbox" name="termscheck" id="termscheck" value="accepted" required/> I agree to terms & Conditions etc									
								</div>
							</div>
							<div class="row pad" style="margin-top:10px;">
								<div class="col-sm-12 standard" style="text-align:center;">
									<input class="btn btn-success" id="submit_button" style="margin:0 30px 0 0;" type="submit" value="Register"/>
									<input class="btn btn-danger" type="reset" value="Reset Form"/>
								</div>
							</div>
						</form>
						
						
						<!--
						<div class="row">
							<div class="col-sm-12">
								User Details</br>
								Forename | Surname | email | required username | Password | Repeat password</br>
								Business Details</br>
								Address Line 1 | Address Line 2 | City | Postcode | Country | Website | Business Sector</br>
								Business Information</br>
								Company Name | Company Profile | Aveage Age range of customers | Avg facebook daily visitors | Lowest priced service | highest priced service</br>
							</div>
						</div>
						-->
						<?php
						break;
					default:
						echo 'Something has gone wrong.';
				}
			}
		?>
			
		<?php include ('./includes/footer.php');?>		
		<!-- END CONTENT -->
	</body>
</html>