<?php
	//Shop Database Connections
	$hostname = "localhost";
	$username = "thecoupo_shop";
	$password = "~:%[\&z];1K){7N";

	$shop_db = mysqli_connect($hostname, $username, $password);
	mysqli_select_db($shop_db, "thecoupo_coupons") or die ("Can't find Shop Database");
?>