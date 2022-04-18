<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';
	include 'langCheck.php';

	protect();

	$title = $_POST['name'];
	$desc = $_POST['description'];
	$snippet = $_POST['snippet'];
	$syntax = $_POST['syntax'];
	$tagsArray = $_POST['tags'];
	$tagsArray = json_decode($tagsArray);
	$flag = $_POST['flag'];
	$snippetId = $_POST['id'];
	$group_id = $_POST['groups'];

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

	if( empty($group_id) ) {
		echo $lang['emptyGroup'];
		exit();
	}

	if( empty($syntax) ) {
		$syntax = 'none';
	}

	$query = $con->prepare("SELECT user_id FROM users WHERE username = ?");
	$query->bind_param("s", $_SESSION['user']);
	$query->execute();
	$query->bind_result($user_id);
	$query->fetch();
	$query->close();

	if( $flag === 'false') {
		$date = date("Y-m-d");
		$stmt = $con->prepare("INSERT INTO snippets VALUES('',?, ?, ?, ?, ?, ?, '', ?)");
		$stmt->bind_param("sssssss", $title, $desc, $syntax, $snippet, $user_id, $date, $group_id);
		$stmt->execute();
		$id = $con->insert_id;
		$stmt->close();

		$q = $con->prepare("INSERT INTO tags_snippets VALUES('', ?, ?, ?)");
		foreach($tagsArray as $tag) {
			$q->bind_param("sss", $id, $tag, $user_id);
			$q->execute();
		}
		$q->close();
		echo 'ok';
	} else if( $flag === 'true' ) {

		$stmt = $con->prepare("UPDATE snippets SET title = ?, description = ?, snippet = ?, group_id = ? WHERE id = ?");
		$stmt->bind_param("sssss", $title, $desc, $snippet, $group_id, $snippetId);
		$stmt->execute();
		$stmt->close();

		$w = $con->query("DELETE FROM tags_snippets WHERE snippets_id = '$snippetId'");
		$q = $con->prepare("INSERT INTO tags_snippets VALUES('', ?, ?, ?)");
		foreach( $tagsArray as $tag ) {
			$q->bind_param("sss", $snippetId, $tag, $user_id);
			$q->execute();
		}
		$q->close();

		echo 'ok';
	}
?>
