<?php
	//Categories Database
	$hostname = "localhost";
	$username = "thecoupo_types";
	$password = "75{4#U28=3I8&0s";

	$dbcats = mysqli_connect($hostname, $username, $password);
	mysqli_select_db($dbcats, "thecoupo_categories") or die ("Can't find categories");
?>