<?php
	include_once('./includes/security.php');
	// Set up Sessions
	
	$page_limit = 15; //Number of items to view on this page
	
	$item_number = filter_input(INPUT_GET, 'item', FILTER_SANITIZE_SPECIAL_CHARS);
	if (isset($item_number) || $item_number != ''){
		
		$itemSetup = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `deal_id` = '$item_number'");
		$item = mysqli_fetch_array($itemSetup);
		$vendor_id = $item['vendor_id'];
		
		$vendor_setup = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `vendor_id` = '$vendor_id'");
		$vendor = mysqli_fetch_array($vendor_setup);
	}
	
	if(isset($_GET['ref'])){		
		$_SESSION['referrer'] = $_GET['ref'];
		header('Location: ./shop.php?item='.$item_number);
	} elseif(isset($_SESSION['referrer'])){
		$referrer = $_SESSION['referrer'];
		unset($_SESSION['referrer']);
	} else {
		unset($_SESSION['referrer']);
	}
	
	$pageTitle = 'The Coupon Hub';
	$metaKeywords = '';
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<script>
			window.fbAsyncInit = function() {
				FB.init({
					appId      : '1703713406526137',
					xfbml      : true,
					version    : 'v2.6'
				});	
			};
			
			(function(d, s, id){
				var js, fjs = d.getElementsByTagName(s)[0];
				if (d.getElementById(id)) {return;}
				js = d.createElement(s); js.id = id;
				js.src = "//connect.facebook.net/en_US/sdk.js";
				fjs.parentNode.insertBefore(js, fjs);
			}(document, 'script', 'facebook-jssdk'));
		</script>
		<?php				
			$search_string = filter_input(INPUT_GET, 'search', FILTER_SANITIZE_SPECIAL_CHARS);
			if($search_string == true){
				$search_terms = str_ireplace(" ","|",$search_string);
			}
			
			$cat_reference = filter_input(INPUT_GET, 'cat', FILTER_SANITIZE_SPECIAL_CHARS);
			$page = filter_input(INPUT_GET, 'pg', FILTER_SANITIZE_SPECIAL_CHARS);
			
			include_once('./includes/meta.php');			
			include_once('./includes/scripts.php');	

			$locationString	=  filter_input(INPUT_POST, 'loc', FILTER_SANITIZE_SPECIAL_CHARS);
			if ($locationString == true){
				// Search for items by location
				$findLocationVendorsSetup = mysqli_query($secure_db,"SELECT `vendor_id` FROM `vendors` WHERE `town` = '$locationString'");
				//$findLocationVendors = mysqli_fetch_array($findLocationVendorsSetup);
				
				$locationItemArray = array();
				
				while ($row = mysqli_fetch_array($findLocationVendorsSetup)){
					$vendorFound = $row['vendor_id'];
					$locationDealsSetup = mysqli_query($evms_db,"SELECT `deal_id` FROM `coupon_details` WHERE `vendor_id` = '$vendorFound'");
					while ($locationDeals = mysqli_fetch_assoc($locationDealsSetup)){
						
						
						if ($locationItemArray == ''){
							$locationItemArray = $locationItemArray.$locationDeals['deal_id'];
						} else {
							$locationItemArray = $locationItemArray.'|';
							$locationItemArray = $locationItemArray.$locationDeals['deal_id'];
						}
					}	
				}				
			}
			
			if (!isset($page)){
				$page = 0;
				$current_page = $page * $page_limit;
				$next_page = $page * $page_limit + $page_limit;
				$previous_page = 0;
			} else {
				$current_page = $page * $page_limit;
				$next_page = $page * $page_limit + $page_limit;
				$previous_page = $page * $page_limit - $page_limit;
			}			
		?>		
	</head>
	<body class="container-fluid <?php echo $indexBg;?>">
		<!-- START CONTENT -->
		<?php include ('./includes/'.$indexNav.'.php');?>	
		<div class="row toprow">
			<div class="col-sm-3 pad">
				<div class="col-sm-12 catbar">
					<?php
						include ('./includes/categories.php');
					?>
				</div>
			</div>
			<div class="col-sm-9">
				<?php
					if (isset($search_terms)){
						// Check for keywords
						$coupon_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `keywords` REGEXP '$search_terms' ORDER BY `time_generated` DESC LIMIT $current_page,$page_limit");
						$max_page_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_details`");	
					} elseif(isset($cat_reference)){
						// Category selected
						$category_setup = mysqli_query($dbcats,"SELECT * FROM `categories` WHERE `code` = '$cat_reference'");
						$category = mysqli_fetch_array($category_setup);
						$category = $category['id'];
						$coupon_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `category` = '$category' ORDER BY `time_generated` DESC LIMIT $current_page,$page_limit");
						$max_page_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `category` = '$category'");
					} elseif (isset($locationString)){
						// Location search
						
						
						//$coupon_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `keywords` REGEXP '$search_terms' ORDER BY `time_generated` DESC LIMIT $current_page,$page_limit");
						
						
						$max_page_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_details`");	
					} else {
						$coupon_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_details` ORDER BY `time_generated` DESC LIMIT $current_page,$page_limit");
						$max_page_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_details`");						
					}
					$max_page = ceil((mysqli_num_rows($max_page_setup) / $page_limit)) - 1;
					
					include ('./includes/storefront.php');
				?>
			</div>
		</div>
		<?php include ('./includes/footer.php');?>		
		<!-- END CONTENT -->
	</body>
</html>