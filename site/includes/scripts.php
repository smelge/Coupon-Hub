<!-- Bootstrap Load -->
<link href="./css/bootstrap.min.css" rel="stylesheet">
<link href="./css/coupon-hub.css" rel="stylesheet">			
<link rel="stylesheet" href="./css/font-awesome.min.css">
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="./js/bootstrap.min.js"></script>

<?php
	// Essentials
	include('./includes/modals.php');
	include('./includes/fonts.php');
	//include('./includes/social_media.php');
	if (isset($item_number)){
		if ($item_number == true){			
			include('./includes/facebook.php');
		}
	}	
?>
<!-- Editor -->
<script type="text/javascript" src="./js/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
	tinymce.init({
		selector: "textarea",
		plugins: [
		"autolink lists link charmap anchor",
		"searchreplace visualblocks fullscreen",
		"insertdatetime table paste hr"
		],
		menubar:false,
		toolbar: "undo redo | styleselect | bold italic underline strikethrough subscript superscript| alignleft aligncenter alignright alignjustify | bullist numlist outdent indent blockquote | table | hr"
	});
	
	// Prevent bootstrap dialog from blocking focusin
	$(document).on('focusin', function(e) {
		if ($(e.target).closest(".mce-window").length) {
			e.stopImmediatePropagation();
		}
	});
</script>

<?php
	function getCategory($cat_id){
		// Open connection to Categories DB
		include('./includes/category_connect.php');
		$g_cat_setup = mysqli_query($dbcats,"SELECT * FROM `categories` WHERE `id` = '$cat_id'");
		$get_cat = mysqli_fetch_array($g_cat_setup);
		// Find category name for ID
		return $get_cat['name'];
	}
	
	function dateEdit($dateIn){
		if ($dateIn == '0000-00-00'){
			$dateOut = 'No Result';
		} else {
			$dateOut = date("jS M Y", strtotime($dateIn));
		}		
		return $dateOut;
	}	
	
	function timeEdit($timeIn){
		$timeOut = date("H:ia jS M Y", strtotime($timeIn));
		return $timeOut;
	}

	function getUsername($findUsername){		
		$inputUserId = mysqli_query($secure_db,"SELECT * FROM `members` WHERE `user_id` = '$findUsername'");
		$getUserId = mysqli_fetch_array($inputUserId);
		// Find username for ID
		return $getUserId['username'];
	}

	$get_mail_setup = mysqli_query($secure_db,"SELECT * FROM `private_messages` WHERE `to_id` = '$user_id' AND `status` = 0");
	$get_mail = mysqli_num_rows($get_mail_setup);
	
	function phoneFormat($inputNumber){
		$inputNumber = str_ireplace(" ","",$inputNumber);
		$numberArray = str_split($inputNumber);
		$output = '';
		for ($phoneLoop = 0;$phoneLoop < count($numberArray);$phoneLoop++){
			if ($phoneLoop == 4 || $phoneLoop == 7){
				$output .= ' '.$numberArray[$phoneLoop];
			} else {
				$output .= $numberArray[$phoneLoop];
			}
		}
		return $output;
	}
	
	function getVendor($vendorInput){
		include("../includes/members_link.php");
		$partner_setup = mysqli_query($secure_db,"SELECT * FROM `vendors` WHERE `vendor_id` = '$vendorInput'");
		$partner = mysqli_fetch_array($partner_setup);
		return $partner['company'];
	}
	
	include_once ('./includes/alerts.php');
?>

<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<![endif]-->