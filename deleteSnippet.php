<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protectAdmin();

	$ids = $_POST['ids'];

	$query = $con->prepare("DELETE FROM snippets WHERE id = ?");

	foreach($ids as $id){
		$con->query("DELETE FROM tags_snippets WHERE snippets_id = '$id'");
		$query->bind_param("s", $id);
		$query->execute();
	}
	$query->close();
	echo 'ok';
?>
