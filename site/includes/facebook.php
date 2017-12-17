<!-- Facebook OpenGraph -->

<?php
	$item_setup = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `deal_id` = '$item_number'");
	$item = mysqli_fetch_array($item_setup);
	
	$itemTitle = $item['title'] .'- only &pound;'.$item['discount'].' ('.$item['percent'].'% Off)';
?>

<meta property="fb:app_id" content="1703713406526137"/> 
<?php
	if (isset($referrer)){
		echo '<meta property = "og:url" content="http://www.thecouponhub.co.uk/shop.php?item='.$item_number.'&referrer='.$referrer.'"/>';
	} else {
		echo '<meta property = "og:url" content="http://www.thecouponhub.co.uk/shop.php?item='.$item_number.'"/>';
	}
?>

<meta property = "og:type" content="product"/>
<meta property = "og:title" content="<?php echo $itemTitle;?>"/>
<meta property = "og:description" content="<?php echo $item['snippet'];?>"/>
<meta property = "og:image:width" content="<?php echo $item['img_width'];?>"/>
<meta property = "og:image:height" content="<?php echo $item['img_height'];?>"/>
<meta property = "og:image" content="http://www.thecouponhub.co.uk/assets/item-images/<?php echo $item['image'];?>"/>

<meta property="product:original_price:amount" content="<?php echo $item['full_price'];?>" /> 
<meta property="product:original_price:currency" content="gbp" />
<!-- VAT REQUIRED -->
<!--
<meta property="product:pretax_price:amount" content="Sample Pre-tax Price: " /> 
<meta property="product:pretax_price:currency" content="Sample Pre-tax Price: " /> 
<meta property="product:price:amount" content="Sample Price: " /> 
<meta property="product:price:currency" content="Sample Price: " /> 

<meta property="product:shipping_cost:amount" content="Sample Shipping Cost: " /> 
<meta property="product:shipping_cost:currency" content="Sample Shipping Cost: " /> 
<meta property="product:weight:value" content="Sample Weight: Value" /> 
<meta property="product:weight:units" content="Sample Weight: Units" /> 
<meta property="product:shipping_weight:value" content="Sample Shipping Weight: Value" /> 
<meta property="product:shipping_weight:units" content="Sample Shipping Weight: Units" /> 
-->
<meta property="product:sale_price:amount" content="<?php echo $item['discount'];?>" /> 
<meta property="product:sale_price:currency" content="gbp" />
<meta property="product:sale_price_dates:start" content="<?php echo $item['start_date'];?>" /> 
<meta property="product:sale_price_dates:end" content="<?php echo $item['end_date'];?>" />
