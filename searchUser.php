<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protectAdmin();

	$t = $_POST['user'];
	$flag = $_POST['flag'];

	$text = "%".$t."%";

	$data = array();
	if( $flag == 'no' ) {
		$query = $con->prepare("SELECT user_id, username, email, joined, active FROM users WHERE username LIKE ? AND banned = '0' LIMIT ?");
	} else if( $flag == 'yes' ) {
		$query = $con->prepare("SELECT user_id, username, email, joined, active FROM users WHERE username LIKE ? AND banned = '1' LIMIT ?");
	}
	$query->bind_param("ss", $text, $numUsers);
	$query->execute();
	$query->bind_result($user_id, $user, $mail, $joined, $active);
	while($query->fetch()){
		$data['user_id'][] = $user_id;
		$data['username'][] = $user;
		$data['email'][] = $mail;
		$data['joined'][] = $joined;
		$data['active'][] = $active;
	}
	$query->close();

	echo json_encode($data);

?>
