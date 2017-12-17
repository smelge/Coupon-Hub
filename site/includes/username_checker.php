<?php
	$hostname = "localhost";
	$db_username = "thecoupo_member";
	$password = ",y{68:%_53en3)Y";
	
	if(isset($_POST["username"]))
	{
		if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) AND strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
			die();
		}
		$mysqli = new mysqli($hostname,$db_username,$password, 'thecoupo_userbase');
		if ($mysqli->connect_error){
			die('Could not connect to database!');
		}
	   
		$username = filter_var($_POST["username"], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW|FILTER_FLAG_STRIP_HIGH);
	   
		$statement = $mysqli->prepare("SELECT `username` FROM `members` WHERE `username`=?");
		$statement->bind_param('s', $username);
		$statement->execute();
		$statement->bind_result($username);
		if($statement->fetch()){
			die('<i style="color:red;" class="fa fa-close"> Unavailable</i>');
		} else {			
			die('<i style="color:green;" class="fa fa-check"> Available</i>');
		}
	}
?>