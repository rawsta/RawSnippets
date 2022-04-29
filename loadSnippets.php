<?php

	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protectAdmin();

	$pageNum = (int)$_POST['page'] - 1;
	$position = $pageNum * $numSnip;

	$data = array();

	$query = $con->prepare("SELECT id, user_id, title, description, syntax, public FROM snippets LIMIT ?,?");

	$query->bind_param("ii", $position, $numSnip);
	$query->execute();
	$query->store_result();
	$query->bind_result($snippet_id, $user_id, $title, $description, $syntax, $public);

	$q = $con->prepare("SELECT username FROM users WHERE user_id = ?");
	while($query->fetch()){
		$q->bind_param("s", $user_id);
		$q->execute();
		$q->bind_result($username);
		$q->fetch();

		$data['user_id'][] = $user_id;
		$data['snippet_id'][] = $snippet_id;
		$data['username'][] = $username;
		$data['title'][] = $title;
		$data['description'][] = $description;
		$data['syntax'][] = $syntax;
		$data['public'][] = $active;
	}
	$q->close();
	$query->close();

	echo json_encode($data);

?>
