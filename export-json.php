<?php

	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protect();

	$query = $con->prepare("SELECT user_id FROM users WHERE username = ?");
	$query->bind_param("s", $_SESSION['user']);
	$query->execute();
	$query->bind_result($user_id);
	$query->fetch();
	$query->close();

	$data = array();

	$query = $con->prepare("SELECT id, title, syntax, description, date, snippet, group_id FROM snippets WHERE user_id = ?");
	$query->bind_param("s", $user_id);
	$query->execute();
	$query->bind_result($id, $title, $syntax, $description, $date, $snippet, $group_id);
	while($query->fetch()){
		$data['id'][] = $id;
		$data['title'][] = $title;
		$data['syntax'][] = $syntax;
		$data['description'][] = $description;
		$data['date'][] = $date;
		$data['snippet'][] = $snippet;
		$data['group_id'][] = $group_id;
	}
	$query->close();

	$tagArray = array();

	$query = $con->prepare("SELECT tags FROM tags_snippets WHERE snippets_id = ?");
	foreach($data['id'] as $tempId){
		$query->bind_param("s", $tempId);
		$query->execute();
		$query->bind_result($tags);
		while($query->fetch()){
			$tagArray[] = "#".$tags;
		}

		$tagsList = implode(", ", $tagArray);
		$data['tags'][] = $tagsList;
		unset($tagsList);
		unset($tagArray);
	}
	$query->close();

	$query = $con->prepare("SELECT id, name, user_id FROM groups WHERE user_id = ?");
	$query->bind_param("s", $user_id);
	$query->execute();
	$query->bind_result($id, $name, $user_id);
	while($query->fetch()){
		$data["groupId"][] = $id;
		$data["groupName"][] = $name;
		$data["groupUserId"][] = $user_id;
	}
	$query->close();

	$filename = uniqid() . ".json";

	header("Content-disposition: attachment; filename=$filename");
	header("Content-type: text/plain");

	echo json_encode($data);

?>