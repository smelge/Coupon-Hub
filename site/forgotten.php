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
		
			$type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_SPECIAL_CHARS);
		?>
	</head>
	<body class="container-fluid">
		<!-- START CONTENT -->
		<?php 
			include ('./includes/ch-header.php');
		?>		
		
		<?php
			switch($type){				
				case 'resend':
				?>
					<div class="row toprow">			
						<div class="col-sm-12 standard">
							<div class="col-sm-12 form-heading">
								Resend Activation Email
							</div>
							<div class="col-sm-4 col-sm-offset-4">
								<form class="form-horizontal" action="./includes/forgot_process.php" method="POST">
									<div class="form-group" style="text-align:center;">
										<label for="email">Registered Email</label>
										<input class="form-control" id="email" required name="email" type="email" placeholder="email@domain.com"/>
										<input type="hidden" name="type" value="activate"/>
									</div>
									<div class="form-group" style="text-align:center;">
										<input class="btn btn-success" type="submit" value="Resend Activation Email"/>
									</div>
								</form>
							</div>
						</div>
					</div>
				<?php
				break;
				default:
				?>
					<div class="row toprow">			
						<div class="col-sm-12 standard">
							<div class="col-sm-12 form-heading">
								Forgotten Password or Username
							</div>
							<div class="col-sm-4 col-sm-offset-4">
								<form class="form-horizontal" action="./includes/forgot_process.php" method="POST">
									<div class="form-group" style="text-align:center;">
										<label for="email">Registered Email</label>
										<input class="form-control" id="email" required name="email" type="email" placeholder="email@domain.com"/>
										<input type="hidden" name="type" value="password"/>
									</div>
									<div class="form-group" style="text-align:center;">
										<input class="btn btn-success" type="submit" value="Email my details"/>
									</div>
								</form>
							</div>
						</div>
					</div>
				<?php
				break;			
			}
		?>
		<?php include ('./includes/footer.php');?>		
		<!-- END CONTENT -->
	</body>
</html>