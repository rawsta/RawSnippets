<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protectAdmin();

	$t = $_POST['title'];

	$text = "%".$t."%";

	$data = array();
	$query = $con->prepare("SELECT id, user_id, title, description, syntax, public FROM snippets WHERE title LIKE ? LIMIT ?");
	$query->bind_param("ss", $text, $numSnip);
	$query->execute();
	$query->store_result();
	$query->bind_result($id, $user_id, $title, $description, $syntax, $public);
	$q = $con->prepare("SELECT username FROM users WHERE user_id = ?");
	while( $query->fetch() ) {
		$q->bind_param("s", $user_id);
		$q->execute();
		$q->bind_result($username);
		$q->fetch();

		$data['snippet_id'][] = $id;
		$data['user_id'][] = $user_id;
		$data['username'][] = $username;
		$data['title'][] = $title;
		$data['description'][] = $description;
		$data['syntax'][] = $syntax;
		$data['public'][] = $public;
	}
	$q->close();
	$query->close();

	echo json_encode($data);

?>
