<?php
	//Shop Database Connections
	$hostname = "#";
	$username = "#";
	$password = "#";

	$shop_db = mysqli_connect($hostname, $username, $password);
	mysqli_select_db($shop_db, "#") or die ("Can't find Shop Database");
?>
