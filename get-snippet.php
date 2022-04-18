<?php
	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protect();

	$id = $_POST['id'];
	$flag = $_POST['flag'];

	$query = $con->prepare("SELECT user_id FROM users WHERE username = ?");
	$query->bind_param("s", $_SESSION['user']);
	$query->execute();
	$query->bind_result($user_id);
	$query->fetch();
	$query->close();

	if($flag === 'true') {
		$query = $con->prepare("SELECT snippet FROM snippets WHERE id = ? AND user_id = ?");
		$query->bind_param("ss", $id, $user_id);
		$query->execute();
		$query->bind_result($code);
		$query->fetch();
		$query->close();

		echo htmlspecialchars($code, ENT_QUOTES, "UTF-8");
	} else {
		$query = $con->prepare("SELECT title, syntax, description, snippet FROM snippets WHERE id = ? AND user_id = ?");
		$query->bind_param("ss", $id, $user_id);
		$query->execute();
		$query->bind_result($title, $syntax, $description, $snippet);
		$query->fetch();
		$query->close();

		$array = array();
		$array['title'] = $title;
		$array['syntax'] = $syntax;
		$array['description'] = $description;
		$array['snippet'] = $snippet;
		echo json_encode($array, JSON_HEX_QUOT | JSON_HEX_TAG);
	}


?>
