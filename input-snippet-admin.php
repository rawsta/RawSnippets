<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';
	include 'langCheck.php';

	protectAdmin();

	$title = $_POST['name'];
	$desc = $_POST['description'];
	$snippet = $_POST['snippet'];
	$tagsArray = $_POST['tags'];
	$tagsArray = json_decode($tagsArray);
	$snippetId = $_POST['id'];

	if( empty($title) ) {
		echo $lang['emptyTitle'];
		exit();
	}
	if( empty($snippet) ) {
		echo $lang['emptySnippet'];
		exit();
	}
	if( empty($tagsArray) ) {
		echo $lang['emptyTag'];
		exit();
	}

	$stmt = $con->prepare("UPDATE snippets SET title = ?, description = ?, snippet = ? WHERE id = ?");
	$stmt->bind_param("ssss", $title, $desc, $snippet, $snippetId);
	$stmt->execute();
	$stmt->close();

	$query = $con->prepare("SELECT user_id FROM snippets WHERE id = ?");
	$query->bind_param("s", $snippetId);
	$query->execute();
	$query->bind_result($user_id);
	$query->fetch();
	$query->close();

	$w = $con->query("DELETE FROM tags_snippets WHERE snippets_id = '$snippetId'");
	$q = $con->prepare("INSERT INTO tags_snippets VALUES('', ?, ?, ?)");
	foreach( $tagsArray as $tag ) {
		$q->bind_param("sss", $snippetId, $tag, $user_id);
		$q->execute();
	}
	$q->close();
	echo 'ok';
?>
