<?php
	//EVMS Basic Database
	$hostname = "localhost";
	$username = "thecoupo_evms";
	$password = "E44[|q@45@E|w2o";

	$evms_db = mysqli_connect($hostname, $username, $password);
	mysqli_select_db($evms_db, "thecoupo_coupons") or die ("Can't find Coupon Database");
?>