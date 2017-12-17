<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="The Coupon Hub - The best coupon deals on the web">
<meta name="keywords" content="<?php echo $metaKeywords;?>">
<?php
	if (isset($item_number)){
		// Displaying an item, set Author as Vendor
		echo '<meta name="author" content="'.$vendor['company'].'">';
	} else {
		// Not displaying an item, show Author as site developer
		echo '<meta name="author" content="Tavy Fraser">';
	}
?>
<link rel="icon" href="./assets/couponhub-favicon.png" type="image/x-icon">


<title><?php echo $pageTitle;?></title>