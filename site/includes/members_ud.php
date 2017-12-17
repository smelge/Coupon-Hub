<?php
	//Categories Database
	$hostname = "#";
	$username = "#";
	$password = "#";

	$mem_update = mysqli_connect($hostname, $username, $password);
	mysqli_select_db($mem_update, "#") or die ("Can't find Member Database");
?>
