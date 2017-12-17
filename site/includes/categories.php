<div class="col-sm-12">
	<a href="./shop.php">
		<div class="col-sm-12 category-item">
			<div class="col-sm-2 category-icon"><i class="fa fa-arrows-h"></i></div>
			<div class="col-sm-10 category-desc">
				All
				<?php
					// Count all items in DB
					$total_items = mysqli_query($shop_db,"SELECT * FROM `coupon_details` WHERE `start_date` <= '$today_date' AND `end_date` >= '$today_date'");
					$all_categories = mysqli_num_rows($total_items);
				?>
				<div class="pull-right"><?php echo $all_categories;?></div>
			</div>			
		</div>
	</a>

	<?php
		$cat_set = mysqli_query($dbcats,"SELECT * FROM `categories` ORDER BY `name` ASC");
		while ($cat=mysqli_fetch_array($cat_set)){
			// Count number of items in items DB in this category
			// If 0 - do not display
			$current_cat = $cat['id'];
			$shop_setup = mysqli_query($shop_db,"SELECT * FROM `coupon_details` WHERE `category` = '$current_cat' AND(`start_date` <= '$today_date' AND `end_date` >= '$today_date')");
			$category_number = mysqli_num_rows($shop_setup);
			
			if ($category_number != 0){
				echo '<a href="./shop.php?cat='.$cat['code'].'">';
					echo '<div class="col-sm-12 category-item">';
						echo '<div class="col-sm-2 category-icon"><i class="fa fa-'.$cat['icon'].'"></i></div>';
						echo '<div class="col-sm-10 category-desc">';
						echo $cat['name'];
						echo '<div class="pull-right">'.$category_number.'</div>';
						echo '</div>';
					echo '</div>';
				echo '</a>';
			}
		}
	?>
</div>