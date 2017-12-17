<?php
	//Shopping Basket Database
	$hostname = "#";
	$username = "#";
	$password = "#";

	$db_basket = mysqli_connect($hostname, $username, $password);
	mysqli_select_db($db_basket, "#") or die ("Can't find baskets");
?>
