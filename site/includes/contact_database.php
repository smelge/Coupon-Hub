<?php
	//Categories Database
	$hostname = "#";
	$username = "#";
	$password = "#";

	$dbContact = mysqli_connect($hostname, $username, $password);
	mysqli_select_db($dbContact, "#") or die ("Can't update Contact Database");
?>
