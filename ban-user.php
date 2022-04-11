<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protectAdmin();

	$ids = $_POST['ids'];
	$flag = $_POST['flag'];

	if( $flag == 'ban' ) {
		$query = $con->prepare("UPDATE users SET banned = 1 WHERE user_id = ?");
		foreach($ids as $id){
			$query->bind_param("s", $id);
			$query->execute();
		}
		$query->close();
		echo 'ok';
	} else if( $flag == 'unban' ) {
		$query = $con->prepare("UPDATE users SET banned = 0 WHERE user_id = ?");
		foreach($ids as $id){
			$query->bind_param("s", $id);
			$query->execute();
		}
		$query->close();
		echo 'ok';
	} else {
		echo 'error';
	}

?>
