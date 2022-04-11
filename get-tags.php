<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protect();
	
	$id = $_POST['id'];
	$array = array();

	$query = $con->prepare("select tags from tags_snippets where snippets_id = ?");
	$query->bind_param("s", $id);
	$query->execute();
	$query->bind_result($tags);
	while($query->fetch()){
		$array[] = $tags;
	}
	$query->close();
	echo json_encode($array);
?>