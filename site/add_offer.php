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
		?>
		<!-- Datepicker Stuff -->
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<script>
			$(function() {
				$( "#start-date,#end-date" ).datepicker({
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
		<form class="form-horizontal" action="./evms/generate.php" method="POST" enctype="multipart/form-data">
			<div class="row">
				<div class="col-sm-12 standard pad">
					<div class="col-sm-12">
						<div class="form-heading">
							Add an Offer
						</div>						
						<div class="form-group">
							<label for="image">Add an image</label>
							<input type="file" id="image" name="offerimage" required/>
						</div>						
						<div class="form-group">
							<label for="title">Offer Title</label>
							<input class="form-control" id="title" required name="title" type="text" placeholder="e.g. Meal at Morrisons Pizzeria"/>
						</div>
						<div class="form-group">
							<label for="description">Offer Description</label>
							<textarea rows="7" class="form-control" id="description" name="description"></textarea>
						</div>
						<div class="form-group">
							<label for="keywords">Keywords - separated by commas</label>
							<input class="form-control" id="keywords" required name="keywords" type="text" placeholder="e.g. meal,pizza,food,restaurant"/>
						</div>
						<div class="form-group">
							<div class="col-sm-6" style="padding-left:0;">
								<label for="full-price">Full Price</label>
								<div class="input-group">
									<div class="input-group-addon">£</div>								
									<input class="form-control" id="full-price" required name="full-price" type="number" placeholder="e.g. 19" style="z-index:1"/>								
								</div>
							</div>
							<div class="col-sm-6" style="padding-right:0;">
								<label for="discount-price">Discounted Price</label>
								<div class="input-group">
									<div class="input-group-addon">£</div>								
									<input class="form-control" id="discount-price" required name="discount-price" type="number" placeholder="e.g. 11" style="z-index:1"/>								
								</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="col-sm-4" style="padding-left:0;">
								<label for="start-date">Start of Offer</label>
								<input type="text" id="start-date" name="start-date" class="form-control" style="z-index:1000"/>
							</div>
							<div class="col-sm-4">
								<label for="end-date">End of Offer</label>
								<input type="text" id="end-date" name="end-date" class="form-control" style="z-index:1000"/>
							</div>
							<div class="col-sm-4" style="padding-right:0;">
								<label for="category">Deal Category</label>
								<select class="form-control" id="category" name="category">
									<?php
										$company_name = $_SESSION['company'];
										$company_set = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `company` = '$company_name'");
										$company = mysqli_fetch_array($company_set);
										$company_category = $company['sector'];
										
										$category_setup = mysqli_query($dbcats,"SELECT * FROM `categories` ORDER BY `name` ASC");
										while($category = mysqli_fetch_array($category_setup)){
											if ($company_category == $category['id']){
												echo '<option selected value="'.$category['id'].'">'.$category['name'].'</option>';
											} else {
												echo '<option value="'.$category['id'].'">'.$category['name'].'</option>';
											}
										}
									?>
								</select>
							</div>
						</div>
						<div class="alert alert-info" role="alert">
							We will soon be adding the ability to sell your products with Deals added through The Coupon Hub Shop, 
							as well as postage to enable you to process and send orders direct to the customer.
							</br></br>
							Ideal to reach people Worldwide if you are a local business!
						</div>
						<!--
						<div class="form-group">
							<label for="title">Allow Online Sales through The Coupon Hub?</label>
						</div>
						<div class="form-group">
							<label for="title">Postage (Coming Soon)</label>
							<label for="title">postage cost (per item)</label>
							<label for="title">bulk postage rate (per item)</label>
						</div>		
						-->
						<div class="form-group">
							<label for="total-coupon">How many coupons?</label>
							<input class="form-control" id="total-coupon" required name="total-coupon" type="number" placeholder="e.g. 500"/>
						</div>
						<div class="alert alert-info" role="alert">
							When our Social Network Partnership functions are implemented, you will be able to assign a certain number of coupons
							to your partners, so there will be a guaranteed number of coupons for their Facebook audience.
						</div>
					</div>
				</div>
			</div>
			<div class="row" style="margin-top:10px;">
				<div class="col-sm-12 standard" style="text-align:center;">
					<input type="hidden" name="vendor-id" value="<?php echo $_SESSION['company_id'];?>"/>
					<input class="btn btn-success" id="submit_button" style="margin:0 30px 0 0;" type="submit" value="Generate Coupons"/>
					<input class="btn btn-danger" type="reset" value="Reset Form"/>
				</div>
			</div>
		</form>
		<?php include ('./includes/footer.php');?>		
		<!-- END CONTENT -->
	</body>
</html>