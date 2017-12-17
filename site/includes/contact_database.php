<?php
	//Categories Database
	$hostname = "localhost";
	$username = "thecoupo_contact";
	$password = "&Dgik(960r0Gwr";

	$dbContact = mysqli_connect($hostname, $username, $password);
	mysqli_select_db($dbContact, "thecoupo_userbase") or die ("Can't update Contact Database");
?>