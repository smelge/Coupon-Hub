<?php
	// Send site mail
	include_once ('./members_ud.php'); //$mem_update
	
	$message_id = filter_input(INPUT_POST, 'messageNo', FILTER_SANITIZE_SPECIAL_CHARS);
	$sender = filter_input(INPUT_POST, 'user_id', FILTER_SANITIZE_SPECIAL_CHARS);
	$boxtype = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_SPECIAL_CHARS);
	$recipient = filter_input(INPUT_POST, 'victim_id', FILTER_SANITIZE_SPECIAL_CHARS);
	$title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_SPECIAL_CHARS);
	$message = filter_input(INPUT_POST, 'reply', FILTER_SANITIZE_SPECIAL_CHARS);
	
	$time = date("Y-m-d H:i:s");
	
	$encode_string = $sender.'-'.$recipient.'-'.$time;
	$encoded = md5($encode_string);
	
	$sqlpath = "
		INSERT INTO `private_messages` (
			`ident`,
			`original_ident`,
			`from_id`,
			`to_id`,
			`time_sent`,
			`title`,
			`message`) 
		VALUES (
			'$encoded',
			'$message_id',
			'$sender',
			'$recipient',
			'$time',
			'$title',
			'$message')
	";
	if (!mysqli_query($mem_update,$sqlpath)) {
		die(header('Location: ../mail.php?m='.$boxtype.'&result=error'));// Gone Wrong
	} else {				
		header('Location: ../mail.php?m=outbox');
	}
?>