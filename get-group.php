<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protect();

	$id = $_POST['id'];

	$query = $con->prepare("SELECT group_id FROM snippets WHERE id = ?");
	$query->bind_param("s", $id);
	$query->execute();
	$query->bind_result($group_id);
	$query->fetch();
	$query->close();

	$query = $con->prepare("SELECT id, name FROM groups WHERE id = ?");
	$query->bind_param("s", $group_id);
	$query->execute();
	$query->bind_result($id, $name);
	$query->fetch();
	$query->close();

	$data = array();
	$data['id'] = $id;
	$data['name'] = $name;

	echo json_encode($data);

?>
