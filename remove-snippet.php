<?php

	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protect();

	$id = $_POST['id'];

	if(!empty($id)){
		$query = $con->prepare("DELETE FROM tags_snippets WHERE snippets_id = ?");
		$query->bind_param("s", $id);
		$query->execute();
		$query->close();

		$query = $con->prepare("DELETE FROM snippets WHERE id = ?");
		$query->bind_param("s", $id);
		$query->execute();
		$query->close();

		echo "ok";
		exit();
	}else{
		echo "break";
		exit();
	}
?>
