<div class="row">
	<div class="col-sm-8 col-sm-offset-2 equal">
		<div class="col-sm-4 threebar threebar-1">
			Post Deals and Promotions to attract new customers and increase sales
		</div>
		<div class="col-sm-4 threebar threebar-2">
			Choose the membership plan perfect for your needs
		</div>
		<div class="col-sm-4 threebar threebar-3">
			Connect with even more customers and other businesses through social media
		</div>
	</div>
</div>		
<div class="row">
	<div class="col-sm-12 downarrow">
		<i class="fa fa-angle-down" aria-hidden="true"></i>
	</div>
</div>
<div class="row">
	<div class="col-sm-12 padding-0">
		<?php
			if (isset($_SESSION['username'])){
				echo '<a href="./myaccount.php">';
			} else {
				echo '<a data-toggle="modal" data-target="#loginModal">';
			}
		?>		
			<img style="cursor:pointer;" src="./assets/business_infographic.gif" alt="Vendor Infographic" class="img-responsive"/>
		</a>
	</div>
</div>