<?php

	session_start();

	include 'database/connect.php';
	include 'functions.php';
	include 'langCheck.php';

	protectAdmin();

	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = encrypt($_POST['password']);

	if(empty($username)){
		echo $lang['usernameEmpty'];
		exit();
	}
	if(empty($email)){
		echo $lang['emailEmpty'];
		exit();
	}
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		echo $lang['emailInvalid'];
		exit();
	}
	if(empty($password)){
		echo $lang['emptyPassword'];
		exit();
	}
	$query = $con->prepare("SELECT * FROM users WHERE username = ?");
	$query->bind_param("s", $username);
	$query->execute();
	$query->store_result();
	$nm = $query->num_rows;
	$query->close();

	if($nm > 0){
		echo $lang['userExists'];
		exit();
	}

	$query = $con->prepare("SELECT * FROM users WHERE email = ?");
	$query->bind_param("s", $email);
	$query->execute();
	$query->store_result();
	$nm1 = $query->num_rows;
	$query->close();

	if($nm1 > 0){
		echo $lang['mailExists'];
		exit();
	}

	$date = date('Y-m-d');

	$query = $con->prepare("INSERT INTO users (username, password, email, active, joined) VALUES (?, ?, ?, '1', ?)");
	$query->bind_param("ssss", $username, $password, $email, $date);
	$query->execute();
	$query->close();
	echo $query->error;
	echo $con->error;

	echo $lang['addedUser'];

?>