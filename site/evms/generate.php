<?php
	include ('./evms_basic.php');
	
	$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
	$vendor_id = filter_input(INPUT_POST, 'vendor-id', FILTER_SANITIZE_SPECIAL_CHARS);
	//$description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_SPECIAL_CHARS);
	$description = $_POST['description'];
	$snippet_setup = strip_tags($description);
	
	$description = filter_var($description, FILTER_SANITIZE_SPECIAL_CHARS);
	$keywords =  filter_input(INPUT_POST, 'keywords', FILTER_SANITIZE_SPECIAL_CHARS);
		$keywords = str_ireplace(" ",",",$keywords);
		
	$full_price = filter_input(INPUT_POST, 'full-price', FILTER_SANITIZE_SPECIAL_CHARS);
	$discount = filter_input(INPUT_POST, 'discount-price', FILTER_SANITIZE_SPECIAL_CHARS);
	$start_en = filter_input(INPUT_POST, 'start-date', FILTER_SANITIZE_SPECIAL_CHARS);
	$start = date("Y-m-d", strtotime($start_en));
	$end_en = filter_input(INPUT_POST, 'end-date', FILTER_SANITIZE_SPECIAL_CHARS);
	$end = date("Y-m-d", strtotime($end_en));
	$category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_SPECIAL_CHARS);
	$coupon_number = filter_input(INPUT_POST, 'total-coupon', FILTER_SANITIZE_SPECIAL_CHARS);	
	
	$percentage = round((1 - ($discount / $full_price)) * 100,0);	
	
	$snippet_bits = explode(" ",$snippet_setup);
	$snippet = $snippet_bits[0];
	for ($sniploop = 1;$sniploop <=25;$sniploop++){
		$snippet = $snippet.' '.$snippet_bits[$sniploop];
	}
	$snippet = $snippet.'...';
	
	$currentupload = ($_FILES['offerimage'] ['name']);
	$datestring = date_create();
	$datestring = date_timestamp_get($datestring);
	
	// Get image dimensions	
	$imgSize = getimagesize($_FILES['offerimage'] ['name']);
	$imgWidth = $imgSize[0];
	$imgHeight = $imgSize[1];
	
	if ($currentupload == ''){
		header('Location: ../add_offer.php?upload=failed');
	}
	
	move_uploaded_file($_FILES['offerimage'] ['tmp_name'], "../assets/item-images/".$datestring."-{$_FILES['offerimage']['name']}");
	$image_root = $datestring.'-'.$currentupload;	
	
	//insert into db
		
	function generateCouponCode($deal_id){
		include ('./evms_basic.php');
		$unique_code = 0;
		while ($unique_code == 0){
			$Allowed_Characters = 'abcdefghijkmnpqrstuvwxyz23456789ABCDEFGHJLKMNPQRSTUVWXYZ';
			$serial = substr(str_shuffle($Allowed_Characters), 0, 12);	
			
			$coupon_unique_set = mysqli_query($evms_db,"SELECT * FROM `coupon_repo` WHERE `serial` = '$serial'");
			$coupon_unique = mysqli_num_rows($coupon_unique_set);
			if ($coupon_unique == 0){
				$unique_code = 1;
				// Insert code into database
				$sqlpath = "
					INSERT INTO `coupon_repo` (
						`serial`,
						`deal_id`) 
					VALUES (
						'$serial',
						'$deal_id')
				";
				if (!mysqli_query($evms_db,$sqlpath)) {
					die('Not working');// Gone Wrong
				}
			} else {
				$unique_code = 0;
			}
		}
		return $serial;
	}
	
	// Add Deal to relevant DB
	$sqlpath = "
		INSERT INTO `coupon_details` (
			`vendor_id`,
			`category`,
			`title`,
			`snippet`,
			`description`,
			`keywords`,
			`image`,
			`img_width`,
			`img_height`,
			`full_price`,
			`discount`,
			`percent`,
			`start_date`,
			`end_date`) 
		VALUES (
			'$vendor_id',
			'$category',
			'$title',
			'$snippet',
			'$description',
			'$keywords',
			'$image_root',
			'$imgWidth',
			'$imgHeight',
			'$full_price',
			'$discount',
			'$percentage',
			'$start',
			'$end')
	";	
	if (!mysqli_query($evms_db,$sqlpath)) {
		die('Not working');// Gone Wrong
	} else {
		$deal_id = mysqli_insert_id($evms_db);
	}
	
	// Generate Coupons
	for ($i = 1;$i <= $coupon_number;$i++){
		generateCouponCode($deal_id);
	}
	header('Location: ../active_coupons.php');
?>