<!--
	New version:	
	Small boxes, 150 x auto in 3 strips
	Strip 1 slide right, strip 2 slides left
	
	Each box is image with link, percentage overlay
	Each row needs minimum of 15 offers, maximum of 25
	If row cannot be filled, Coming Soon image instead
-->
<?php
	$sliderOverallSet = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `start_date` <= '$today_date' AND `end_date` >= '$today_date'");
	$sliderOverall = mysqli_num_rows($sliderOverallSet);
	//$sliderOverall = 70;
?>

<div class="row">
	<div class="col-sm-12 sliderbar" style="padding:15px 0;">
		<section class="variable slider">
			<?php
				if ($sliderOverall <= 15){
					$maxDeals = 15;
				} else {
					$maxDeals = 25;
				}
			
				$slider1_set = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `start_date` <= '$today_date' AND `end_date` >= '$today_date' ORDER BY `deal_id` DESC LIMIT 0,$maxDeals");
				$slider1Total = mysqli_num_rows($slider1_set);
				$prePopulateSlider1 = $maxDeals - $slider1Total;
				while ($slider1 = mysqli_fetch_array($slider1_set)){
					echo '
						<div>
							<a href="./shop.php?item='.$slider1['deal_id'].'">
								<div class="slider-overlay">'.$slider1['percent'].'% Off</div>
								<img class="img-responsive" style="height:150px;width:auto;" src="./assets/item-images/'.$slider1['image'].'" alt="'.$slider1['title'].'">
							</a>
						</div>
					';
				}
				for ($extraSlider1 = 1;$extraSlider1 <= $prePopulateSlider1;$extraSlider1++){
					echo '<div><img class="img-responsive" style="height:150px;width:auto;" src="./assets/item-images/default.jpg" alt="No item Yet!"></div>';
				}
			?>
		</section>
	</div>
</div>
<div class="row">
	<div class="col-sm-12 sliderbar" style="padding:0 0 15px 0;">
		<?php
			if ($sliderOverall > 25){
				echo '<section dir="rtl" class="inverse slider">';
					$slider2_set = mysqli_query($evms_db,"SELECT * FROM `coupon_details` WHERE `start_date` <= '$today_date' AND `end_date` >= '$today_date' ORDER BY `deal_id` DESC LIMIT 25,$maxDeals");
					$slider2Total = mysqli_num_rows($slider2_set);
					$prePopulateslider2 = $maxDeals - $slider2Total;
					while ($slider2 = mysqli_fetch_array($slider2_set)){
						echo '
							<div>
								<a href="./shop.php?item='.$slider2['deal_id'].'">
									<div class="slider-overlay">'.$slider2['percent'].'% Off</div>
									<img class="img-responsive" style="height:150px;width:auto;" src="./assets/item-images/'.$slider2['image'].'" alt="'.$slider2['title'].'">
								</a>
							</div>
						';
					}
					for ($extraslider2 = 1;$extraslider2 <= $prePopulateslider2;$extraslider2++){
						echo '<div><img class="img-responsive" style="height:150px;width:auto;" src="./assets/item-images/default.jpg" alt="No item Yet!"></div>';
					}
				echo '</section>';
			}
			
			$slidesToShow = $maxDeals - 1;
		?>
	</div>
</div>

<script type="text/javascript">
    $(document).on('ready', function() {      
		$(".variable").slick({
			infinite: true,
			arrows:false,
			slidesToShow:<?php echo $slidesToShow;?>,
			slidesToScroll:1,
			variableWidth: true,
			autoplay:true,
			autoplaySpeed: 2000,
			draggable:true,
			pauseOnHover:true		
		});
		$(".inverse").slick({
			rtl:true,
			arrows:false,
			infinite: true,
			slidesToShow:<?php echo $slidesToShow;?>,
			slidesToScroll:1,
			variableWidth: true,
			autoplay:true,
			autoplaySpeed: 2000,
			draggable:true,
			pauseOnHover:true			
		});
    });
</script>