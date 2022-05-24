<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protect();

	$id = $_POST['id'];

	$query = $con->prepare("SELECT user_id FROM users WHERE username = ?");
	$query->bind_param("s", $_SESSION['user']);
	$query->execute();
	$query->bind_result($user_id);
	$query->fetch();
	$query->close();

	$data = array();
	$temp = array();

	$query = $con->prepare("SELECT id, title, description, syntax, date, public FROM snippets WHERE id = ? AND user_id = ?");
	$query->bind_param("ss", $id, $user_id);
	$query->execute();
	$query->bind_result($snippetId, $title, $description, $syntax, $date, $public);
	$query->fetch();
	$query->close();

	$data['idSnippet'][] = $snippetId;
	$data['title'][] = $title;
	$data['description'][] = $description;
	$data['syntax'][] = $syntax;
	$data['date'][] = $date;
	$data['public'][] = $public;

	$query = $con->prepare("SELECT tags FROM tags_snippets WHERE user_id = ? AND snippets_id = ?");
	$query->bind_param("ss", $user_id, $id);
	$query->execute();
	$query->bind_result($tag);
	while($query->fetch()){
		$temp[] = "#".$tag;
	}
	$query->close();
	$tagsList = implode(", ", $temp);
	$data['tags'][] = $tagsList;

	echo json_encode($data);
	exit();

?>
