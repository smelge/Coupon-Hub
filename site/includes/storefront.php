<?php
	if (isset($item_number) || $item_number != ''){
		
		if ($today_date >= $item['start_date'] && $today_date <= $item['end_date']){
			
			$deal_id = $item['deal_id'];
			$find_stock_set = mysqli_query($evms_db,"SELECT * FROM `coupon_repo` WHERE `deal_id` = '$deal_id' AND `status` = 0");
			$in_stock = mysqli_num_rows($find_stock_set);
			
			echo '<div class="row" style="padding:0 15px;">';
				echo '<div class="col-sm-12 vendor-banner">';
					echo '<a href="./profile.php?id='.$vendor['vendor_id'].'&t=v">';
						if ($vendor['banner'] == true){
							echo '<img src="./assets/vendor-banners/'.$vendor['banner'].'" class="img-responsive" alt="'.$vendor['company'].'"/>';
						} else {
							echo '<span class="banner-overlay">'.$vendor['company'].'</span>';
							echo '<img src="./assets/vendor-banners/default.jpg" class="img-responsive" alt="'.$vendor['company'].'"/>';					
						}
					echo '</a>';
				echo '</div>';
			echo '</div>';			
			echo '<div class="row" style="padding: 0 15px;">';
				echo '<div class="col-sm-12 standard">';
					echo '<div class="col-sm-4 item-image btn-group-vertical">';
						echo '<div class="col-sm-12 padding btn-group-vertical" style="margin-bottom:5px;">';
							if(isset($product)){
								if(in_array($item_number,$product)){
									echo '<a class="btn btn-success btn-lg btn-block" href="./basket.php">Checkout</a>';									
								} else {
									if($in_stock == 0){
										echo '<a class="btn btn-default btn-lg btn-block">Sold Out!</a>';
									} else {
										echo '<a class="btn btn-success btn-lg btn-block" href="./includes/add_deal.php?id='.$item_number.'">Add to Basket</a>';									
									}									
								}
							} else {
								if($in_stock == 0){
									echo '<a class="btn btn-default btn-lg btn-block">Sold Out!</a>';
								} else {
									echo '<a class="btn btn-success btn-lg btn-block" href="./includes/add_deal.php?id='.$item_number.'">Add to Basket</a>';									
								}
							}
							?>
							
							<?php
								if(isset($referrer)){
									echo '<a href="./includes/vendorShare.php?item='.$referrer.'&type=FB" target="_blank" id="FBshare" class="btn btn-primary btn-lg btn-block">Share For '.$vendor['company'].' <i class="fa fa-facebook-square"></i></a>';
									echo '
										<a 
											href="https://twitter.com/share"
											target="_blank"
											class="btn btn-info btn-lg btn-block" 
											data-url="http://www.thecouponhub.co.uk/shop.php?item='.$item_number.'" 
											data-via="thecouponhub">Tweet for '.$vendor['company'].' <i class="fa fa-twitter-square"></i>
										</a>
										<script>
											document.getElementById("FBshare").onclick = function() {
												FB.ui({
													method: "share",
													display: "popup",
													href: "http://www.thecouponhub.co.uk/shop.php?item='.$item_number.'",
												}, function(response){});
												window.open("./includes/vendorShare.php?item='.$referrer.'&type=TWT")
											}
										</script>
									';
								} else {
									echo '<a id="FBshare" class="btn btn-primary btn-lg btn-block">Share this Deal <i class="fa fa-facebook-square"></i></a>';
									echo '
										<a 
											href="https://twitter.com/share"
											target="_blank"
											class="btn btn-info btn-lg btn-block" 
											data-url="http://www.thecouponhub.co.uk/shop.php?item='.$item_number.'" 
											data-via="thecouponhub">Tweet this Deal <i class="fa fa-twitter-square"></i>
										</a>
										<script>
											document.getElementById("FBshare").onclick = function() {
												FB.ui({
													method: "share",
													display: "popup",
													href: "http://www.thecouponhub.co.uk/shop.php?item='.$item_number.'",
												}, function(response){});
											}
										</script>
									';
								}
							?>							
							<script>
								!function(d,s,id){
									var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';
									if(!d.getElementById(id)){
										js=d.createElement(s);
										js.id=id;js.src=p+'://platform.twitter.com/widgets.js';
										fjs.parentNode.insertBefore(js,fjs);
									}
								}
								(document, 'script', 'twitter-wjs');
							</script>
							<!--
							<div 
								style="margin-top:5px;" 
								class="fb-share-button" 
								data-href="http://www.thecouponhub.co.uk/shop.php?item=<?php echo $item_number;?>" 
								data-layout="button_count">
							</div>
							-->
							<?php
							//echo '<a class="btn btn-info btn-lg btn-block" href="#">Share this Deal <i class="fa fa-twitter-square"></i></a>';							
						echo '</div>';
						echo '<img src="./assets/item-images/'.$item['image'].'" class="img-responsive center-block" alt="'.$item['title'].'"/>';
						echo '<hr style="margin:10px 0;">';
						echo 'Was &pound;'.$item['full_price'].'</br>';
						echo 'Now &pound;'.$item['discount'].'!';
						echo '<span style="margin-left: 20px;font-size:18px;" class="badge">'.$item['percent'].'% off</span>';
						echo '<hr style="margin:10px 0;">';
						if($in_stock == 0){
							echo 'Out of Stock!';
						} elseif ($in_stock >= 1 && $in_stock <=10) {
							echo 'Hurry! Only '.$in_stock.' Coupons Remaining!';
						} else {
							echo 'Coupons Remaining: '.$in_stock;
						}
						echo '<hr style="margin:10px 0;">';
						
						echo 'Offer Valid Until: '.dateEdit($item['end_date']);
						echo '<hr>';
						echo '<div style="text-align:center;">';
							//Seller name
							echo '
								<a class="btn btn-lg btn-block btn-default" href="./profile.php?id='.$vendor['vendor_id'].'&t=v">
									'.$vendor['company'].'
								</a>
							';											
							//Seller Phone
							if ($vendor['telephone'] == true){
								echo 'Phone: '.$vendor['telephone'].'</br>';
							}

							// Map will go here
							
							//Seller address
							echo $vendor['house'].'</br>';
							echo $vendor['street'].'</br>';
							echo $vendor['town'].'</br>';
							echo $vendor['postcode'].'</br>';
							echo $vendor['country'].'</br>';
						echo '</div>';
					echo '</div>';				
					echo '<div class="col-sm-8 item-description">';
						echo '<div class="col-sm-12">';
							echo '<h4 class="item-title">'.$item['title'].'</h4><hr>';
							echo html_entity_decode($item['description']);
						echo '</div>';					
					echo '</div>';
				echo '</div>';
			echo '</div>';
		} else {
			echo '<div class="row" style="padding: 0 15px;">';
				echo '<div class="col-sm-12 standard">';
					echo 'Sorry, this offer is not currently available.';
				echo '</div>';
			echo '</div>';
		}
	} else {
		if (mysqli_num_rows($coupon_setup) != 0){
			echo '<div class="row">';
				while($coupon = mysqli_fetch_array($coupon_setup)){
					if ($today_date >= $coupon['start_date'] && $today_date <= $coupon['end_date']){
						echo '<a href="./shop.php?item='.$coupon['deal_id'].'">';
							echo '<div class="col-sm-3 shop-container">';
								$deal_id = $coupon['deal_id'];
								$find_stock_set = mysqli_query($evms_db,"SELECT * FROM `coupon_repo` WHERE `deal_id` = '$deal_id' AND `status` = 0");
								$in_stock = mysqli_num_rows($find_stock_set);
								
								echo '<div class="shop-item">';						
									echo '<img src="./assets/item-images/'.$coupon['image'].'" class="img-responsive" alt="'.$coupon['title'].'"/>';						
									echo '<hr>';									
									echo '<h5 class="shop-item-title">'.$coupon['title'].'</h5>';									
									echo '<hr>';
									/*
									<span style="color:red;font-size:24px;">
										<del>
											&pound;'.$coupon['full_price'].'
										</del>
									</span> 
									&pound;'.$coupon['discount'].'
									*/
									if ($in_stock == 0){
										echo '<b><span style="font-size:18px;" class="label label-default">Sold Out!</span></b>';
									} else {
										echo '<b><span style="font-size:18px;" class="label label-default">'.$coupon['percent'].'% off</span></b>';
									}
								echo '</div>';
							echo '</div>';
						echo '</a>';
					}
				}
			echo '</div>';
		} else {
			echo '<div class="row">';
				echo 'No Results Found';
			echo '</div>';
		}
		
		if ($max_page != 0){
			echo '<div class="row">';
				echo '<nav>';
					echo '<ul class="pagination">';
						if ($page == 0){echo '<li class="disabled">';}else{echo '<li>';}
							echo '<a href="./shop.php?pg=0" aria-label="Previous">';
								echo '<span aria-hidden="true">&laquo;</span>';
							echo '</a>';
						echo '</li>';						
						for($loop_page = 0;$loop_page <= $max_page;$loop_page++){
							if ($loop_page == $page){
								echo '<li class="active"><a href="./shop.php?pg='.$loop_page.'">'.$loop_page.'</a></li>';
							} else {
								echo '<li><a href="./shop.php?pg='.$loop_page.'">'.$loop_page.'</a></li>';
							}							
						}						
						if ($page == $max_page){echo '<li class="disabled">';}else{echo '<li>';}
							echo '<a href="./shop.php?pg='.$max_page.'" aria-label="Next">';
								echo '<span aria-hidden="true">&raquo;</span>';
							echo '</a>';
						echo '</li>';
					echo '</ul>';
				echo '</nav>';
			echo '</div>';
		}
	}
?>