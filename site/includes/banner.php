<div class="row toprow">
	<div class="col-sm-12 vendor-banner center-block">
		<?php
			if ($company['banner'] == true){
				echo '<img src="./assets/vendor-banners/'.$company['banner'].'" class="img-responsive" alt="'.$company['company'].'"/>';
			} else {
				echo '<span class="banner-overlay">'.$company['company'].'</span>';
				echo '<img src="./assets/vendor-banners/default.jpg" class="img-responsive" alt="'.$company['company'].'"/>';					
			}
		?>
	</div>
</div>