<?php
	//Logout
	session_start();
	include_once('./snp_security.php');
	//if cookie set for Remember Me, locate and delete cookie
	
	session_unset();
	session_destroy();
	header('Location: ../../'.$homelink);	
?>