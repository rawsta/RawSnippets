<?php

	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protectAdmin();

	$pageNum = (int)$_POST['page'] - 1;
	$flag = $_POST['flag'];
	$position = $pageNum * $numUsers;

	$data = array();

	if( $flag == 'valid' ) {
		$query = $con->prepare("SELECT user_id, username, email, active, joined FROM users WHERE banned = 0 LIMIT ?, ?");
	} else if( $flag == 'banned' ) {
		$query = $con->prepare("SELECT user_id, username, email, active, joined FROM users WHERE banned = 1 LIMIT ?, ?");
	} else {
		exit();
	}

	$query->bind_param("ii", $position, $numUsers);
	$query->execute();
	$query->bind_result($user_id, $username, $email, $active, $joined);
	while($query->fetch()){
		$data['user_id'][] = $user_id;
		$data['username'][] = $username;
		$data['email'][] = $email;
		$data['active'][] = $active;
		$data['joined'][] = $joined;
	}
	$query->close();

	echo json_encode($data);

?>
