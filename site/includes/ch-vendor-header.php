<div class="nav-bar-container">
	<div class="row vendor-nav-bar">
		<div class="col-sm-2">
			<a href="./index.php">
				<img src="./assets/couponhub-logo.png" alt="The Coupon Hub Logo" class="img-responsive logo-overflow"/>
			</a>
		</div>
		<div class="col-sm-10">
			<div class="row">
				<div class="col-md-12 col-lg-6 col-lg-offset-6 font-16 top-navbar">
					<ul>
						<?php
							if (isset($_SESSION['username'])){
								// Logged in
								echo '
									<a data-toggle="modal" data-target="#logoutModal"><li>Sign Out</li></a>
									<span class="nav-divide">|</span>
								';

								if ($get_mail == 0){
									echo '<a href="./myaccount.php"><li>Account</li></a>';
								} else {
									echo '<a href="./myaccount.php"><li>Account <span class="badge">'.$get_mail.'</span></li></a>';
								}
									
								echo '								
									<span class="nav-divide">|</span>
									<a href="./help.php"><li>Help</li></a>
								
									<span class="nav-divide">|</span>
									Signed in as: '.$_SESSION['username'];
							} else {							
								// Logged out
								echo '
									<a data-toggle="modal" data-target="#loginModal"><li>Sign In</li></a>
									<span class="nav-divide">|</span>
									<a data-toggle="modal" data-target="#registerModal"><li>Register</li></a>
									';
									//<span class="nav-divide">|</span>
									//<a href="#"><li>Help</li></a>
									// Disabled until page completed
							}
						?>					
						
					</ul>
				</div>
			</div>
			<div class="row">
				<div class="col-sm-12 font-16 bottom-navbar">
					<div class="col-sm-12 col-md-6 col-lg-3" style="line-height:48px;">
						<a href="./addoffer.php" class="main-button">Make an Offer</a>
					</div>
					<div class="col-sm-12 col-md-6 col-lg-3" style="line-height:48px;">
						<a href="./shop.php?v=<?php echo str_ireplace(' ','_',$_SESSION['company']);?>" class="main-button">Showroom</a>
					</div>
					<!--<div class="col-md-12 col-lg-3" style="padding-left:0;">-->
					<div class="col-md-12 col-lg-6" style="padding-left:0;">
						<form action="./shop.php" method="POST">
							<div class="input-group searchbar">				
								<span class="input-group-btn">
									<button type="submit" class="btn btn-lg btn-default"><i class="fa fa-search"></i></button>
								</span>
								<input name="search" class="form-control input-lg mySearch" type="text" placeholder="Keywords..." <?php if (isset($_POST['search'])){echo 'value="'.$_POST['search'].'"';}?> aria-describedby="searchButton"/>
							</div>
						</form>
					</div>
					<!-- Location Searchbar coming soon
					<div class="col-md-12 col-lg-3" style="padding-left:0;">
						<form action="./shop.php" method="POST">
							<div class="input-group searchbar">				
								<span class="input-group-btn">
									<button type="submit" class="btn btn-lg btn-default"><i class="fa fa-search"></i></button>
								</span>							
								<input name="loc" class="form-control input-lg mySearch" type="text" placeholder="City or Postcode" <?php if (isset($_POST['loc'])){echo 'value="'.$_POST['loc'].'"';}?> aria-describedby="searchButton"/>
							</div>
						</form>
					</div>
					-->
				</div>
			</div>		
		</div>	
	</div>
	<?php
		if (isset($alertType)){
			echo '
				<style>
					.toprow {margin-top:180px;}
				</style>
				<div class="row" style="padding: 0 30px 0 0;">
					<div class="alert alert-'.$alertState.' alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						'.$alertContent.'
					</div>
				</div>
			';
		} else {
			echo '
				<style>
					.toprow {margin-top:160px;}
				</style>
			';
		}
	?>	
</div>
