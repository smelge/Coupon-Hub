<?php
	include_once('./includes/security.php');
	// Set up Sessions
	//Restricted page, check for eligibility, if not send to index
	if (!isset($_SESSION['username'])){
		header('Location: ./'.$homelink);		
	}
	
	$pageTitle = 'The Coupon Hub';
	$metaKeywords = '';
	
	$user_id = $_SESSION['user_id'];
		
	$mail_total_setup = mysqli_query ($mem_update,"SELECT * FROM `private_messages` WHERE `to_id` = '$user_id' AND `status` != 2 AND `status` != 3 ORDER BY `status` ASC, `time_sent` ASC");
	$mail_unread_setup = mysqli_query ($mem_update,"SELECT * FROM `private_messages` WHERE `to_id` = '$user_id' AND `status` = 0");
	$mail_out_setup = mysqli_query ($mem_update,"SELECT * FROM `private_messages` WHERE `from_id` = '$user_id' AND `sender_action` != 2 ORDER BY `time_sent` DESC");
	$mail_saved_setup = mysqli_query ($mem_update,"SELECT * FROM `private_messages` WHERE `to_id` = '$user_id' AND `status` = 2");
	
	$unread = mysqli_num_rows($mail_unread_setup);
	$outbox = mysqli_num_rows($mail_out_setup);
	$saved = mysqli_num_rows($mail_saved_setup);
	$inbox = mysqli_num_rows($mail_total_setup);
			
	$pagetype = filter_input(INPUT_GET, 'm', FILTER_SANITIZE_SPECIAL_CHARS);
	if (!isset($pagetype)){
		$pagetype = 'inbox';
	}
	$message = filter_input(INPUT_GET, 's', FILTER_SANITIZE_SPECIAL_CHARS);
	$remove = filter_input(INPUT_GET, 'r', FILTER_SANITIZE_SPECIAL_CHARS);
	$action = filter_input(INPUT_GET, 'a', FILTER_SANITIZE_SPECIAL_CHARS);
	if ($action == 's'){
		//Save Mail
		$sqlpath = "UPDATE `private_messages` SET `status` = 2 WHERE `ident` = '$message'";
		if (!mysqli_query($mem_update,$sqlpath)) {
			die('Sorry, we couldn\'t move the message to your Saved folder, because the database is experiencing issues.');// Gone Wrong
		} else {
			header('Location: ./mail.php?m='.$pagetype);
		}
	} elseif ($action == 'd'){
		//Delete Mail
		$sqlpath = "UPDATE `private_messages` SET `status` = 3 WHERE `ident` = '$remove'";
		if (!mysqli_query($mem_update,$sqlpath)) {
			die('Sorry, we couldn\'t delete the message because the database is experiencing issues.');// Gone Wrong
		} else {
			header('Location: ./mail.php?m='.$pagetype);
		}
	} elseif ($action == 'do'){
		//Delete Outbox
		$sqlpath = "UPDATE `private_messages` SET `sender_action` = 2 WHERE `ident` = '$remove'";
		if (!mysqli_query($mem_update,$sqlpath)) {
			die('Sorry, we couldn\'t delete the message because the database is experiencing issues.');// Gone Wrong
		} else {
			header('Location: ./mail.php?m='.$pagetype);
		}
	}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php
			include_once('./includes/meta.php');			
			include_once('./includes/scripts.php');				
		?>
	</head>
	<body class="container-fluid <?php echo $indexBg;?>">
		<!-- START CONTENT -->
		<?php include ('./includes/'.$indexNav.'.php');?>		
		<div class="row toprow">
			<div class="col-sm-12 alert-info" style="margin:10px 0; padding:20px;">
				Messages will be deleted after 30 days, please save any you wish to keep.
			</div>
		</div>
		<div class="row">
			<div class="col-sm-3">
				<div class="col-sm-12 standard">
					<a href="?m=inbox" style="text-decoration:none;">
						<?php
							if ($pagetype == 'inbox'){
								echo '<div class="selected-mailbox topround">';
							} else {
								echo '<div class="mailbox topround">';
							}
						?>
							INBOX
							<?php
								if ($inbox <= 50){
									$inbox_state = 'success';
								} elseif ($inbox > 50 && $inbox <= 75){
									$inbox_state = 'warning';
								} else {
									$inbox_state = 'danger';
								}
							?>
							<div class="progress">
								<div class="progress-bar progress-bar-<?php echo $inbox_state;?>" role="progressbar" aria-valuenow="<?php echo $inbox;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $inbox;?>%;">
									<span style="color:black;font-weight:bold;"><?php echo $inbox;?>%</span>
								</div>
							</div>
						</div>
					</a>
					<a href="?m=outbox" style="text-decoration:none;">
						<?php
							if ($pagetype == 'outbox'){
								echo '<div class="selected-mailbox">';
							} else {
								echo '<div class="mailbox">';
							}
						?>
							OUTBOX
							<?php
								if ($outbox <= 50){
									$outbox_state = 'success';
								} elseif ($outbox > 50 && $outbox <= 75){
									$outbox_state = 'warning';
								} else {
									$outbox_state = 'danger';
								}
							?>
							<div class="progress">
								<div class="progress-bar progress-bar-<?php echo $outbox_state;?>" role="progressbar" aria-valuenow="<?php echo $outbox;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $outbox;?>%;">
									<span style="color:black;font-weight:bold;"><?php echo $outbox;?>%</span>
								</div>
							</div>
						</div>
					</a>
					<a href="?m=saved" style="text-decoration:none;">
						<?php
							if ($pagetype == 'saved'){
								echo '<div class="selected-mailbox bottomround">';
							} else {
								echo '<div class="mailbox bottomround">';
							}
						?>
							SAVED
							<?php
								if ($saved <= 50){
									$saved_state = 'success';
								} elseif ($saved > 50 && $saved <= 75){
									$saved_state = 'warning';
								} else {
									$saved_state = 'danger';
								}
							?>
							<div class="progress">
								<div class="progress-bar progress-bar-<?php echo $saved_state;?>" role="progressbar" aria-valuenow="<?php echo $saved;?>" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $saved;?>%;">
									<span style="color:black;font-weight:bold;"><?php echo $saved;?>%</span>
								</div>
							</div>
						</div>
					</a>
				</div>
			</div>
			<div class="col-sm-9">
				<div class="col-sm-12 standard">
					<?php
						if($message == false){
							//Display Relevant Box
							if ($pagetype == 'outbox'){
								echo '<div class="form-heading">';
									echo 'Outbox';								
								echo '</div>';
								echo '<div class="mail-body">';
									if ($outbox == 0){
										echo '<div class="mail-item">';
											echo '<div class="row">';
												echo '<div class="col-sm-12">No Messages to Display</div>';
											echo '</div>';
										echo '</div>';
									} else {
										while ($read = mysqli_fetch_array($mail_out_setup)){
											echo '<a href="./mail.php?m=outbox&s='.$read['ident'].'">';
												echo '<div class="mail-item">';
													echo '<div class="row">';
														echo '<div class="col-sm-1"></div>';
														echo '<div class="col-sm-8">'.html_entity_decode($read['title']).'</div>';
														echo '<div class="col-sm-3">'.html_entity_decode($read['time_sent']).'</div>';
													echo '</div>';
												echo '</div>';
											echo '</a>';							
										}									
									}
								echo '</div>';
							} elseif ($pagetype == 'saved'){
								echo '<div class="form-heading">';
									echo 'Saved Messages';								
								echo '</div>';
								echo '<div class="mail-body">';
									if ($saved == 0){
										echo '<div class="mail-item">';
											echo '<div class="row">';
												echo '<div class="col-sm-12">No Messages to Display</div>';
											echo '</div>';
										echo '</div>';
									} else {
										while ($read = mysqli_fetch_array($mail_saved_setup)){
											echo '<a href="./mail.php?m=saved&s='.$read['ident'].'">';
												echo '<div class="mail-item">';
													echo '<div class="row">';
														echo '<div class="col-sm-1"></div>';
														echo '<div class="col-sm-8">'.html_entity_decode($read['title']).'</div>';
														echo '<div class="col-sm-3">'.$read['time_sent'].'</div>';
													echo '</div>';
												echo '</div>';
											echo '</a>';							
										}							
									}
								echo '</div>';
							} else {
								echo '<div class="form-heading">';
									echo 'Inbox';								
								echo '</div>';
								echo '<div class="mail-body">';
									if ($inbox == 0){
										echo '<div class="mail-item">';
											echo '<div class="row">';
												echo '<div class="col-sm-12">No Messages to Display</div>';
											echo '</div>';
										echo '</div>';
									} else {
										while ($read = mysqli_fetch_array($mail_total_setup)){
											echo '<a href="./mail.php?m=inbox&s='.$read['ident'].'">';
												echo '<div class="mail-item">';
													if ($read['status'] == 0){
														echo '<div class="row">';
															echo '<div class="col-sm-1"><span style="margin-left:20px;background:green;" class="badge">NEW</span></div>';
															echo '<div class="col-sm-8">'.html_entity_decode($read['title']).'</div>';
															echo '<div class="col-sm-3">'.$read['time_sent'].'</div>';
														echo '</div>';
													} else {
														echo '<div class="row">';
															echo '<div class="col-sm-1"></div>';
															echo '<div class="col-sm-8">'.html_entity_decode($read['title']).'</div>';
															echo '<div class="col-sm-3">'.$read['time_sent'].'</div>';
														echo '</div>';
													}
												echo '</div>';
											echo '</a>';							
										}						
									}
								echo '</div>';
							}
						} else {
							// Display Message
							$read_setup = mysqli_query($mem_update,"SELECT * FROM `private_messages` WHERE `ident` = '$message'");
							$read = mysqli_fetch_array($read_setup);
							
							if($read['status'] == 0 && $read['from_id'] != $user_id){
								//update mail from unread to read
								$sqlpath = "UPDATE `private_messages` SET `status` = 1 WHERE `ident` = '$message'";
								if (!mysqli_query($mem_update,$sqlpath)) {
									die('The database is experiencing issues.');// Gone Wrong
								}
							}
							
							if ($read['from_id'] != $user_id || $read['to_id'] != $user_id){
								echo html_entity_decode($read['title']).'<hr>';
								
								if ($read['from_id'] == $_SESSION['user_id']){
									$mailFrom = '<span style="text-transform: capitalize;">'.$_SESSION['username'];
									/*
									if ($_SESSION['company'] == true){
										$mailFrom = $mailFrom .' at '.$_SESSION['company'];
									}*/
									$mailFrom = $mailFrom .'</span>';
								} else {
									$findUsername = $read['from_id'];
									$inputUserId = mysqli_query($mem_update,"SELECT * FROM `members` WHERE `user_id` = '$findUsername'");
									$getUserId = mysqli_fetch_array($inputUserId);
									$toPerson = $getUserId['username'];
									$mailFrom = '<span style="text-transform: capitalize;">'.$toPerson.'</span>';
								}
								if ($read['to_id'] == $_SESSION['user_id']){
									$mailTo = '<span style="text-transform: capitalize;">'.$_SESSION['username'];
									/*if ($_SESSION['company'] == true){
										$mailTo = $mailTo .' at '.$_SESSION['company'];
									}*/
									$mailTo = $mailTo .'</span>';
								} else {
									$findUsername = $read['to_id'];
									$inputUserId = mysqli_query($mem_update,"SELECT * FROM `members` WHERE `user_id` = '$findUsername'");
									$getUserId = mysqli_fetch_array($inputUserId);
									$toPerson = $getUserId['username'];
									$mailTo = '<span style="text-transform: capitalize;">'.$toPerson.'</span>';
								}
								
								echo 'From: '.$mailFrom.'</br>To: '.$mailTo.'</br>Time: '.timeEdit($read['time_sent']).'<hr>';
								
								echo html_entity_decode($read['message']).'<hr>';
								echo '<div class="btn-group">';
									if ($read['from_id'] != $user_id){
										//Check if user sent the mail / it was in their outbox
										echo '<a class="btn btn-success" role="button" data-toggle="collapse" href="#replyBox" aria-expanded="false" aria-controls="replyBox">Reply</a>';
										if ($pagetype != 'saved'){
											echo '<a class="btn btn-info" href="./mail.php?m=saved&s='.$message.'&a=s">Save</a>';
										}									
									}
									if ($read['from_id'] == $user_id){
										echo '<a class="btn btn-danger" href="./mail.php?m=inbox&r='.$message.'&a=do">Delete</a>';
									} else {
										echo '<a class="btn btn-danger" href="./mail.php?m=inbox&r='.$message.'&a=d">Delete</a>';
									}								
								echo '</div>';
								echo '<div class="collapse" id="replyBox">';
									echo '<hr>';
									echo '<div class="pad">';
										echo '<form class="form-horizontal" action="./includes/send.php" method="POST" enctype="multipart/form-data">';
											echo '<input type="hidden" name="messageNo" value="'.$message.'"/>';
											echo '<input type="hidden" name="user_id" value="'.$user_id.'"/>';
											echo '<input type="hidden" name="victim_id" value="'.$read['from_id'].'"/>';
											echo '<input type="hidden" name="type" value="'.$pagetype.'"/>';
											echo '<div class="form-group">';
												echo '<label for="title">Title</label>';
												echo '<input class="form-control" id="title" required name="title" type="text" value="re: '.$read['title'].'"/>';
											echo '</div>';
											echo '<div class="form-group">';
												echo '<label for="reply">Reply</label>';
												echo '<textarea class="form-control" id="reply" name="reply">';
													echo '<span style="color:#666666;"><p></p><h5 style="margin-bottom:0;color:#666666;">Previous Message</h5><hr style="margin-top:5px;">'.$read['message'].'</span>';
												echo '</textarea>';
											echo '</div>';
											echo '<div class="form-group">';
												echo '<input class="btn btn-success" type="submit" value="Send"/>';
											echo '</div>';
										echo '</form>';
									echo '</div>';
								echo '</div>';
							} else {							
								echo 'Message cannot be retrieved';
							}
						}
					?>
				</div>
			</div>
			
		</div>
		<?php include ('./includes/footer.php');?>		
		<!-- END CONTENT -->
	</body>
</html>