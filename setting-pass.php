<?php

	session_start();

	if( isset($_SESSION['admin']) ) {
		$admin = $_SESSION['admin'];
	}

	include 'database/connect.php';
	include 'functions.php';
	include 'langCheck.php';

	protectAdmin();

	$oldPass = $_POST['oldpass'];
	$newPass = $_POST['newpass'];
	$repPass = $_POST['reppass'];

	if( empty($oldPass) || empty($newPass) || empty($repPass) ) {
		echo $lang['fillAllFields'];
		exit();
	}

	$oldPass = encrypt($oldPass);
	$query = $con->prepare("SELECT * FROM admin WHERE username = ? AND password = ?");
	$query->bind_param("ss", $admin, $oldPass);
	$query->execute();
	$query->store_result();
	$nm = $query->num_rows;
	$query->close();

	if($nm == 0){
		echo $lang['passwordInvalid'];
		exit();
	}
	if($newPass !== $repPass){
		echo $lang['passwordsNotMatch'];
		exit();
	}

	$newPass = encrypt($newPass);
	$query = $con->prepare("UPDATE admin SET password = ? WHERE username = ?");
	$query->bind_param("ss", $newPass, $admin);
	$query->execute();
	$query->close();

	echo $lang['passwordChanged'];

?>
