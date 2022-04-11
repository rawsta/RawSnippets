<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protectAdmin();

	$id = $_POST['id'];
	$array = array();

	$query = $con->prepare("SELECT tags FROM tags_snippets WHERE snippets_id = ?");
	$query->bind_param("s", $id);
	$query->execute();
	$query->bind_result($tags);
	while($query->fetch()){
		$array[] = $tags;
	}
	$query->close();
	echo json_encode($array);
?>