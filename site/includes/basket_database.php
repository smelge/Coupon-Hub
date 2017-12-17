<?php
	//Shopping Basket Database
	$hostname = "localhost";
	$username = "thecoupo_grabs";
	$password = "fI%HA@/Gfh1G:DU";

	$db_basket = mysqli_connect($hostname, $username, $password);
	mysqli_select_db($db_basket, "thecoupo_baskets") or die ("Can't find baskets");
?>