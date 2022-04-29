<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protectAdmin();

	$id = $_POST['id'];

	$query = $con->prepare("SELECT title, description, syntax, snippet FROM snippets WHERE id = ?");
	$query->bind_param("s", $id);
	$query->execute();
	$query->bind_result($title, $description, $syntax, $snippet);
	$query->fetch();
	$query->close();
	$array = array();
	$array['title'] = $title;
	$array['description'] = $description;
	$array['syntax'] = $syntax;
	$array['snippet'] = $snippet;
	echo json_encode($array, JSON_HEX_QUOT | JSON_HEX_TAG);


?>