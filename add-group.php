<?php

	session_start();
	include "functions.php";
	include 'database/connect.php';

	include 'langCheck.php';

	protect();

	$name = $_POST['name'];
	if(empty($name)){
		echo $lang['groupNameCantBeEmpty'];
		exit();
	}

	$user = $_SESSION['user'];

	$query = $con->prepare("SELECT user_id FROM users WHERE username = ?");
	$query->bind_param("s", $user);
	$query->execute();
	$query->bind_result($id);
	$query->fetch();
	$query->close();

	$query = $con->prepare("SELECT * FROM groups WHERE name = ? AND user_id = ?");
	$query->bind_param("ss", $name, $id);
	$query->execute();
	$query->store_result();
	$nm = $query->num_rows;
	$query->close();

	if($nm > 0){
		echo $lang['groupNameTaken'];
		exit();
	}

	$query = $con->prepare("INSERT INTO groups VALUES('', ?, ?)");
	$query->bind_param("ss", $name, $id);
	$query->execute();
	$query->close();

	echo "ok";

?>
