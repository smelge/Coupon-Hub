<?php
	//Categories Database
	$hostname = "localhost";
	$username = "thecoupo_member";
	$password = ",y{68:%_53en3)Y";

	$secure_db = mysqli_connect($hostname, $username, $password);
	mysqli_select_db($secure_db, "thecoupo_userbase") or die ("Can't find Member Database");
?>