<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protect();

	$id = $_POST['id'];

	$query = $con->prepare("UPDATE snippets SET group_id = null WHERE group_id = ?");
	$query->bind_param("s", $id);
	$query->execute();
	$query->close();

	$query = $con->prepare("DELETE FROM groups WHERE id = ?");
	$query->bind_param("s", $id);
	$query->execute();
	$query->close();

	echo 'ok';
?>
