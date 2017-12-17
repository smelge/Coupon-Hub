<?php
	//Categories Database
	$hostname = "localhost";
	$username = "thecoupo_memupd";
	$password = "-Y1mw@Ds;!\Cj^H";

	$mem_update = mysqli_connect($hostname, $username, $password);
	mysqli_select_db($mem_update, "thecoupo_userbase") or die ("Can't find Member Database");
?>