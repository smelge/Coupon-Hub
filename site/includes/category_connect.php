<?php
	//Categories Database
	$hostname = "#";
	$username = "#";
	$password = "#";

	$dbcats = mysqli_connect($hostname, $username, $password);
	mysqli_select_db($dbcats, "#") or die ("Can't find categories");
?>
