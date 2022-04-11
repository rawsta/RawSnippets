<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';
	include 'langCheck.php';

	protectAdmin();

	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$activate = $_POST['activate'];
	$id = $_POST['id'];

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

	$query = $con->prepare("SELECT * FROM users WHERE username = ? AND username =
		(SELECT username FROM users WHERE user_id = ?)");
	$query->bind_param("ss", $username, $id);
	$query->execute();
	$query->store_result();
	$res = $query->num_rows;
	$query->close();

	if($res === 0){
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
	}

	$query = $con->prepare("SELECT * FROM users WHERE email = ? AND email =
		(SELECT email FROM users WHERE user_id = ?)");
	$query->bind_param("ss", $email, $id);
	$query->execute();
	$query->store_result();
	$res = $query->num_rows;
	$query->close();

	if($res === 0){
		$query = $con->prepare("SELECT * FROM users WHERE email = ?");
		$query->bind_param("s", $email);
		$query->execute();
		$query->store_result();
		$nm = $query->num_rows;
		$query->close();

		if($nm > 0){
			echo $lang['mailExists'];
			exit();
		}
	}

	if(!empty($password)){
		$password = encrypt($password);
		$query = $con->prepare("update users set username = ?, email = ?, password = ?, active = ? where user_id = ?");
		$query->bind_param("sssss", $username, $email, $password, $activate, $id);
	}
	if(empty($password)){
		$query = $con->prepare("update users set username = ?, email = ?, active = ? where user_id = ?");
		$query->bind_param("ssss", $username, $email, $activate, $id);
	}

	$query->execute();
	$query->close();

	echo $lang['saved'];
?>