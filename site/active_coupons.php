<?php
	// Set up Sessions
	include_once('./includes/security.php');
	//Restricted page, check for eligibility, if not send to index
	if (!isset($_SESSION['company'])){
		header('Location: ./'.$homelink);		
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
		
			$company_name = $_SESSION['company'];
			$company_set = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `company` = '$company_name'");
			$company = mysqli_fetch_array($company_set);
			$vendor_id = $company['vendor_id'];
			
			$deals_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `vendor_id` = '$vendor_id' ORDER BY `end_date` DESC");
			$dealsCount = mysqli_num_rows($deals_setup);
		?>
		<!-- Datepicker Stuff -->
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<?php
			$extendString = '#extend-date-1';
			for ($extendLoop = 2;$extendLoop <= $dealsCount;$extendLoop++){
				$extendString .= ',#extend-date-'.$extendLoop;
			}
		?>
		<script>
			$(function() {
				$( "<?php echo $extendString;?>" ).datepicker({
					minDate: 0,
					dateFormat: 'dd-mm-yy'
				});
			});
		</script>
	</head>
	<body class="container-fluid vendor-body">
		<!-- START CONTENT -->
		<?php 
			include ('./includes/ch-vendor-header.php');
			include ('./includes/banner.php');
		?>
		<div class="row pad" style="margin-top:10px;">
			<div class="col-sm-12">
				<div class="table-background">
					<table class="table table-bordered table-hover table-responsive" style="border:2px solid black;">
						<tr style="border:1px solid black;">
							<th style="border:1px solid black;">Deal</th>
							<th style="border:1px solid black;">Category</th>
							<th style="border:1px solid black;">Full Price</th>
							<th style="border:1px solid black;">Discount Price</th>
							<th style="border:1px solid black;">Discount %</th>
							<th style="border:1px solid black;">Start</th>
							<th style="border:1px solid black;">End</th>
							<th style="border:1px solid black;">Total Coupons</th>
							<th style="border:1px solid black;">Sold Coupons</th>
							<th style="border:1px solid black;">Redeemed Coupons</th>
							<th style="border:1px solid black;">Income</th>
							<th style="border:1px solid black;">Charges</th>
							<th style="border:1px solid black;">Profit</th>
							<th style="border:1px solid black;">Edit</th>
						</tr>
						<?php
							$extendCount = 1;
							while ($deals = mysqli_fetch_array($deals_setup)){
								$deal_id = $deals['deal_id'];
								
								echo '
									<!-- Date Extension modal -->
									<div class="modal fade" id="extendModal'.$deal_id.'" tabindex="-1" role="dialog" aria-labelledby="extendModal'.$deal_id.'Label">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
													<h4 class="modal-title" id="extendModal'.$deal_id.'Label">Extend an Offer</h4>
												</div>
												<div class="modal-body">
													<div class="row">
														<div class="col-sm-10 col-sm-offset-1">
															<form action="./includes/extend_deal.php" method="POST">
																<input type="hidden" name="deal_id" value="'.$deal_id.'"/>
																<label for="extend-date-'.$extendCount.'">Select a date to extend to</label>
																<input type="text" id="extend-date-'.$extendCount.'" name="extend-date" class="form-control" style="z-index:1000"/>
																</br>
																<input class="btn btn-success" type="submit" value="Update!"/>
																<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
															</form>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								';
								
								$coupons_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_repo` WHERE `deal_id` = '$deal_id'");
								$total_coupons = mysqli_num_rows($coupons_setup);
								$bought_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_repo` WHERE `deal_id` = '$deal_id' AND (`status` = 1 OR `status` = 2)");
								$bought = mysqli_num_rows($bought_setup);
								$redeemed_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_repo` WHERE `deal_id` = '$deal_id' AND `status` = '3'");
								$redeemed = mysqli_num_rows($redeemed_setup);
								
								$discount = round((1 - ($deals['discount'] / $deals['full_price'])) * 100,0);								
								$find_cat = $deals['category'];
								$category_setup = mysqli_query($dbcats,"SELECT * FROM `categories` WHERE `id` = '$find_cat'");
								$category = mysqli_fetch_array($category_setup);
								$this_cat = $category['name'];								
								
								if ($deals['end_date'] < $today_date){
									echo '<tr class="bg-danger">';
								} else {
									echo '<tr>';
								}								
									echo '<td style="border:1px solid black;">'.$deals['title'].'</td>';
									echo '<td style="border:1px solid black;">'.getCategory($deals['category']).'</td>';
									echo '<td style="border:1px solid black;">&pound;'.$deals['full_price'].'</td>';
									echo '<td style="border:1px solid black;">&pound;'.$deals['discount'].'</td>';
									
									echo '<td style="border:1px solid black;">';
										//round(($deals['full_price'] / $deals['discount']) * 100,0) 
										echo $discount;
									echo '%</td>';
									
									echo '<td style="border:1px solid black;">'.dateEdit($deals['start_date']).'</td>';
									echo '<td style="border:1px solid black;">'.dateEdit($deals['end_date']).'</td>';
									echo '<td style="border:1px solid black;">'.$total_coupons.'</td>';
									echo '<td style="border:1px solid black;">'.$bought.'</td>';
									echo '<td style="border:1px solid black;">'.$redeemed.'</td>';
									echo '<td style="border:1px solid black;">&pound;'.$deals['discount'] * $redeemed.'</td>';
									echo '<td style="border:1px solid black;">&pound'.$redeemed.'</td>';
									echo '<td style="border:1px solid black;">&pound'. (($deals['discount'] * $redeemed) - $redeemed) .'</td>';
									if ($deals['end_date'] < $today_date){
										echo '<td style="border:1px solid black;"><a class="btn btn-success" data-toggle="modal" data-target="#extendModal'.$deal_id.'">Extend Deal</a></td>';
									} else {
										echo '<td style="border:1px solid black;"><a class="btn btn-info" href="#" disabled="disabled">End Deal Early</a></td>';
									}
									
								echo '</tr>';
								$extendCount++;
							}
						?>
					</table>
				</div>
			</div>	
		</div>
		<?php include ('./includes/footer.php');?>		
		<!-- END CONTENT -->
	</body>
</html>