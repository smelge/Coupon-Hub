<?php
	// Process alert states

	$alertType = filter_input(INPUT_GET, 'result', FILTER_SANITIZE_SPECIAL_CHARS);
	
	switch($alertType){
		case 1:
			// Logged in
			$alertState = 'success';
			$alertContent = 'Welcome back, '.$_SESSION['username'].'!';
			break;
		case 2:
			// Login Failed
			$alertState = 'danger';
			$alertContent = '
				Sorry, something went wrong with your log in. 
				Please check your username and password then try again.
				<br><br>
				Alternatively, if you have forgotten either one, try 
				<a href="./recover_password.php">Recover Password/Username</a>
			';
			break;
		case 3:
			// Registration Success
			$alertState = 'success';
			$alertContent = '
				Registration Successful!</br></br>
				There\'s one more step before you can use The Coupon Hub...<br><br>
				Simply check the email address you registered with for your Activation email 
				and follow the link to Activate your account.
			';
			break;
		case 4:
			// Registration Failed
			$alertState = 'danger';
			$alertContent = '
				Registration Failed.</br></br>
				Sorry, something went wrong with your registration. Please try again later.
			';
			break;
		case 5:
			// Database failure
			$alertState = 'danger';
			$alertContent = '
				We apologise for the inconvenience, but the The Coupon Hub Database appears to be temporarily
				broken.<br><br>
				We will get this fixed as soon as possible. In the meantime, keep an eye on our Twitter
				for updates.
			';
			break;
		case 6:
			//Email success
			$alertState = 'success';
			$alertContent = 'Thanks for sending us a message, we\'ll get back to you as soon as possible.';
			break;
		case 7:
			// Email Failure
			$alertState = 'danger';
			$alertContent = '
				Sorry, but something went wrong with your message. Please try again later, 
				or email us directly at: enquiries@thecouponhub.co.uk
			';
			break;
		case 8:
			// Add item to basket
			$alertState = 'success';
			$alertContent = 'Item added to Basket!';
			break;
		case 9:
			// Remove item from basket
			$alertState = 'warning';
			$alertContent = 'Item removed from Basket';
			break;
		case 10:
			// Shared Basket
			$alertState = 'success';
			$alertContent = 'Your Basket has been shared!';
			break;
		case 11:
			// Donation Successful
			$alertState = 'success';
			$alertContent = 'This has not been implemented';
			break;
		case 12:
			// Donation failed
			$alertState = 'danger';
			$alertContent = 'This has not been implemented';
			break;
		case 13:
			//Profile update success
			$alertState = 'success';
			$alertContent = 'Your profile has been updated!';
			break;
		case 14:
			// Profile update failed
			$alertState = 'danger';
			$alertContent = 'Sorry, an issue occurred with updating your profile. Please try again later.';
			break;
		case 15:
			// User not yet verified
			$alertState = 'warning';
			$alertContent = '
				Your account is not yet Verified.</br>
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
			';
			break;
		case 16:
			// Unassigned
			$alertState = '';
			$alertContent = '';
			break;
		case 17:
			// Unassigned
			$alertState = '';
			$alertContent = '';
			break;
		case 18:
			// User doesn't exist
			$alertState = 'danger';
			$alertContent = 'Username does not exist.</br?</br>Please check you typed it correctly.';
			break;
		case 19:
			// unassigned
			$alertState = '';
			$alertContent = '';
			break;
	}