<?php

	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protect();

	$value = $_POST['value'];
	$id = $_POST['id'];

	$query = $con->prepare("SELECT user_id FROM users WHERE username = ?");
    $query->bind_param("s", $_SESSION['user']);
    $query->execute();
    $query->bind_result($user_id);
    $query->fetch();
    $query->close();

	$query = $con->prepare("UPDATE snippets SET public = ? WHERE user_id = ? AND id = ?");
	$query->bind_param("sss", $value, $user_id, $id[0]);
	$query->execute();
	$query->close();

?>