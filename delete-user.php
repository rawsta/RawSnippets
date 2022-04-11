<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protectAdmin();
	
	$ids = $_POST['ids'];
	
	$query = $con->prepare("delete from users where user_id = ?");
	foreach($ids as $id){
		$query->bind_param("s", $id);
		$query->execute();
	}
	$query->close();
	echo 'ok';
?>