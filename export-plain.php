<?php

	session_start();
	include 'database/connect.php';
	include 'functions.php';
	include 'langCheck.php';

	protect();

	$query = $con->prepare("SELECT user_id FROM users WHERE username = ?");
	$query->bind_param("s", $_SESSION['user']);
	$query->execute();
	$query->bind_result($user_id);
	$query->fetch();
	$query->close();

	$data = array();

	$query = $con->prepare("SELECT id, title, description, date, snippet FROM snippets WHERE user_id = ?");
	$query->bind_param("s", $user_id);
	$query->execute();
	$query->bind_result($id, $title, $description, $date, $snippet);
	while($query->fetch()){
		$data['id'][] = $id;
		$data['title'][] = $title;
		$data['description'][] = $description;
		$data['date'][] = $date;
		$data['snippet'][] = $snippet;
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

	for($i = 0; $i < count($data['id']); $i++){
		$title = $data['title'];
		$date = $data['date'];
		$description = $data['description'];
		$snippet = $data['snippet'];
		$tags = $data['tags'];

		$createdT = $lang['created'];
		$descriptionT = $lang['description'];
		$snippetT = $lang['snippet'];
		$tagsT = $lang['tags'];

$string .= "
*** $title[$i] - $createdT($date[$i]) ***

$descriptionT:

$description[$i]

$snippetT:

$snippet[$i]

$tagsT:

$tags[$i]

===================================================

";


}

$filename = uniqid() . ".txt";

header("Content-disposition: attachment; filename=$filename");
header("Content-type: text/plain");

echo $string;


?>