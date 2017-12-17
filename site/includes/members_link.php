<?php
	//Categories Database
	$hostname = "#";
	$username = "#";
	$password = "#";

	$secure_db = mysqli_connect($hostname, $username, $password);
	mysqli_select_db($secure_db, "#") or die ("Can't find Member Database");
?>
