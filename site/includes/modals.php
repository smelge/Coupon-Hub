<!-- Modal Schemes -->
<?php
	$modaltype = filter_input(INPUT_GET, 'result', FILTER_SANITIZE_SPECIAL_CHARS);
	
	switch ($modaltype){
		case 1: //Login success
			echo '
				<script>
					$(document).ready(function(){
						$("#loginsuccess").modal("show");
					})
				</script>
				<div class="modal fade" id="loginsuccess" tabindex="-1" role="dialog" aria-labelledby="loginsuccessLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="loginsuccess">Successfully Logged In</h4>
							</div>
							<div class="modal-body">
								Welcome back, '.$_SESSION['username'].'!
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			';
		break;
		case 2: //Login Failed			
			echo '
				<script>
					$(document).ready(function(){
						$("#loginfail").modal("show");
					})
				</script>
				<div class="modal fade" id="loginfail" tabindex="-1" role="dialog" aria-labelledby="loginfailLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="loginfailLabel">Log In Failed</h4>
							</div>
							<div class="modal-body">
								Sorry, something went wrong with your log in. 
								Please check your username and password then try again.
								<br><br>
								Alternatively, if you have forgotten either one, try 
								<a href="./recover_password.php">Recover Password/Username</a>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			';
		break;
		case 3: //Register success			
			echo "
				<script>
					$(document).ready(function(){
						$('#registersuccess').modal('show');
					})
				</script>
			";
			echo '
				<div class="modal fade" id="registersuccess" tabindex="-1" role="dialog" aria-labelledby="registersuccessLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="registersuccessLabel">Registration Successful!</h4>
							</div>
							<div class="modal-body">
								There\'s one more step before you can use The Coupon Hub...<br><br>
								Simply check the email address you registered with for your Activation email 
								and follow the link to Activate your account.
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			';
		break;
		case 4: //Register failed			
			echo '
				<script>
					$(document).ready(function(){
						$("#registerfail").modal("show");
					})
				</script>
				<div class="modal fade" id="registerfail" tabindex="-1" role="dialog" aria-labelledby="registerfailLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="registerfailLabel">Registration Failed</h4>
							</div>
							<div class="modal-body">
								Sorry, something went wrong with your registration. Please try again later.
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			';
		break;
		case 5: //Database Failure			
			echo '
				<script>
					$(document).ready(function(){
						$("#dbfail").modal("show");
					})
				</script>
				<div class="modal fade" id="dbfail" tabindex="-1" role="dialog" aria-labelledby="dbfailLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="dbfailLabel">Panic Mode Activated</h4>
							</div>
							<div class="modal-body">
								We apologise for the inconvenience, but the The Coupon Hub Database appears to be temporarily
								broken.<br><br>
								We will get this fixed as soon as possible. In the meantime, keep an eye on our Twitter
								for updates.
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			';
		break;
		case 6: //Email Success			
			echo '
				<script>
					$(document).ready(function(){
						$("#emailsuccess").modal("show");
					})
				</script>
				<div class="modal fade" id="emailsuccess" tabindex="-1" role="dialog" aria-labelledby="emailsuccessLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="emailsuccessLabel">Email Sent</h4>
							</div>
							<div class="modal-body">
								Thanks for sending us a message, we\'ll get back to you as soon as possible.
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			';
		break;
		case 7: //Email Failure			
			echo '
				<script>
					$(document).ready(function(){
						$("#emailfail").modal("show");
					})
				</script>
				<div class="modal fade" id="emailfail" tabindex="-1" role="dialog" aria-labelledby="emailfailLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="emailfailLabel">Email Sending Failed</h4>
							</div>
							<div class="modal-body">
								Sorry, but something went wrong with your message. Please try again later, 
								or email us directly at: enquiries@thecouponhub.co.uk
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			';
		break;
		case 8: //Add item to wishlist			
			echo '
				<script>
					$(document).ready(function(){
						$("#addwishlist").modal("show");
					})
				</script>
				<div class="modal fade" id="addwishlist" tabindex="-1" role="dialog" aria-labelledby="addwishlistLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="addwishlistLabel">Item added to Wishlist</h4>
							</div>
							<div class="modal-body">
								You can add more stuff, share your wishlist or collect the items.
							</div>
							<div class="modal-footer">				
								<button type="button" class="btn btn-primary">View List</button>
								<button type="button" class="btn btn-primary">Share List</button>
								<button type="button" class="btn btn-primary">Redeem List</button>
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			';
		break;
		case 9: //Remove item from wishlist			
			echo '
				<script>
					$(document).ready(function(){
						$("#removewishlist").modal("show");
					})
				</script>
				<div class="modal fade" id="removewishlist" tabindex="-1" role="dialog" aria-labelledby="removewishlistLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="removewishlistLabel">Item Removed from Wishlist</h4>
							</div>
							<div class="modal-body">
								But there\'s plenty more offers to try out!
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-primary">View List</button>
								<button type="button" class="btn btn-primary">Share List</button>
								<button type="button" class="btn btn-primary">Redeem List</button>
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			';
		break;
		case 10: //Shared wishlist			
			echo '
				<script>
					$(document).ready(function(){
						$("#sharewishlist").modal("show");
					})
				</script>
				<div class="modal fade" id="sharewishlist" tabindex="-1" role="dialog" aria-labelledby="sharewishlistLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="sharewishlistLabel">Wishlist shared!</h4>
							</div>
							<div class="modal-body">
								Now all your friends can join in on the offers!
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-primary">View List</button>
								<button type="button" class="btn btn-primary">Redeem List</button>
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			';
		break;
		case 11: //Donation Successful			
			echo '
				<script>
					$(document).ready(function(){
						$("#donatesuccess").modal("show");
					})
				</script>
				<div class="modal fade" id="donatesuccess" tabindex="-1" role="dialog" aria-labelledby="donatesuccessLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="donatesuccessLabel">Thank you, '.$_SESSION['username'].'</h4>
							</div>
							<div class="modal-body">
								...
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary">Save changes</button>
							</div>
						</div>
					</div>
				</div>
			';
		break;
		case 12: //Donation failed			
			echo '
				<script>
					$(document).ready(function(){
						$("#donatefail").modal("show");
					})
				</script>
				<div class="modal fade" id="donatefail" tabindex="-1" role="dialog" aria-labelledby="donatefailLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="donatefailLabel">Modal title</h4>
							</div>
							<div class="modal-body">
								...
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary">Save changes</button>
							</div>
						</div>
					</div>
				</div>
			';
		break;
		case 13: //Profile update successful			
			echo '
				<script>
					$(document).ready(function(){
						$("#profilesuccess").modal("show");
					})
				</script>
				<div class="modal fade" id="profilesuccess" tabindex="-1" role="dialog" aria-labelledby="profilesuccessLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="profilesuccessLabel">Modal title</h4>
							</div>
							<div class="modal-body">
								...
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary">Save changes</button>
							</div>
						</div>
					</div>
				</div>
			';
		break;
		case 14: //Profile update failed			
			echo '
				<script>
					$(document).ready(function(){
						$("#profilefail").modal("show");
					})
				</script>
				<div class="modal fade" id="profilefail" tabindex="-1" role="dialog" aria-labelledby="profilefailLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="profilefailLabel">Modal title</h4>
							</div>
							<div class="modal-body">
								...
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-primary">Save changes</button>
							</div>
						</div>
					</div>
				</div>
			';
		break;
		case 15: //user not yet verified			
			echo '
				<script>
					$(document).ready(function(){
						$("#unverified").modal("show");
					})
				</script>				
				<div class="modal fade" id="unverified" tabindex="-1" role="dialog" aria-labelledby="unverifiedLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="unverifiedLabel">Account not activated yet</h4>
							</div>
							<div class="modal-body">
								Please check your email for the Verification email.</br></br>
								If it\'s not even in your Spam folder, then enter your email below.
								</br></br>
								<form action="./includes/forgot_process.php?type=activate" method="POST">
									<div class="input-group">
										<input type="email" name="email" class="form-control" type="text" required placeholder="Email" aria-describedby="resendButton"/>
										<span class="input-group-btn">
											<input type="submit" name="resendButton" value="Resend Activation Email" class="btn btn-info"/>
										</span>
									</div>
								</form>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			';
		break;
		case 16: //Unassigned		
			echo '
				<script>
					$(document).ready(function(){
						$("#").modal("show");
					})
				</script>
			';
		break;
		case 17: //Unassigned		
			echo '
				<script>
					$(document).ready(function(){
						$("#").modal("show");
					})
				</script>
			';
		break;
		case 18: //User doesn't exist		
			echo '
				<script>
					$(document).ready(function(){
						$("#exists").modal("show");
					})
				</script>
				<div class="modal fade" id="exists" tabindex="-1" role="dialog" aria-labelledby="existsLabel">
					<div class="modal-dialog" role="document">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
								<h4 class="modal-title" id="existsLabel">Username doesn\'t exist</h4>
							</div>
							<div class="modal-body">
								Please check you typed it correctly.
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
			';
		break;
	}
?>

<!-- These ones work -->
<?php
	if (!isset($_SESSION['username'])){
		// Login
		echo '
			<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="loginModalLabel">Log In to The Coupon Hub</h4>
						</div>
						<div class="modal-body">
							<form class="inline-form" action="./includes/verify.php" method="POST">
									<input class="form-control" type="text" name="user" placeholder="Username">
									<input class="form-control" type="password" name="password" placeholder="password">
									<input class="form-control btn btn-success" type="submit" value="Submit">
								</form>
						</div>
						<div class="modal-footer" style="text-align:center;">
							<div class="btn-group">
								<a class="btn btn-default" href="#" data-dismiss="modal" data-toggle="modal" data-target="#registerModal">Register</a>
								<a class="btn btn-default" href="./forgotten.php">Forgotten Username / Password?</a>
								<a class="btn btn-default" data-dismiss="modal">Close</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		';
		//Login from Checkout
		echo '
			<div class="modal fade" id="checkoutLoginModal" tabindex="-1" role="dialog" aria-labelledby="checkoutLoginModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="checkoutLoginModalLabel">Log In to The Coupon Hub</h4>
						</div>
						<div class="modal-body">
							<h4>Already a Member?</h4>
							Simply Log In to add these items to your account!</br></br>				
							<form class="inline-form" action="./includes/verify.php" method="POST">
								<input class="form-control" type="text" name="user" placeholder="Username">
								<input class="form-control" type="password" name="password" placeholder="password">
								<input class="form-control btn btn-success" type="submit" value="Log In" style="margin-top:5px;">
							</form>
							<hr>
							<h4>Not a Member yet?</h4>
							Registering an account on The Coupon Hub takes a couple of minutes and allows you access to 
							all the great deals on The Coupon Hub. Put more copy here please.
						</div>
						<div class="modal-footer" style="text-align:center;">
							<div class="btn-group">
								<a class="btn btn-default" href="#" data-dismiss="modal" data-toggle="modal" data-target="#registerModal">Register</a>
								<a class="btn btn-default" href="./forgotten.php">Forgotten Username / Password?</a>
								<a class="btn btn-default" data-dismiss="modal">Close</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		';
		// Register
		echo '			
			<div class="modal fade" id="registerModal" tabindex="-1" role="dialog" aria-labelledby="registerModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="registerModalLabel">Join The Coupon Hub!</h4>
						</div>
						<div class="modal-body">
							<div class="btn-group">
								<a class="btn btn-default" href="./register.php?type=user">Register as a User</a>
								<a class="btn btn-default" href="./register.php?type=business">Register as a Business</a>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
		';
	} else {
		// User logged in - load logout modal
		echo '
			<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h4 class="modal-title" id="logoutModalLabel">Log Out of The Coupon Hub</h4>
						</div>
						<div class="modal-body">
							<a class="btn btn-danger" href="./includes/logout.php">Log Out?</a>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						</div>
					</div>
				</div>
			</div>
		';
	}
?>