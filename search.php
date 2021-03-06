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

	$t = $_POST['text'];
	$text = "%".$t."%";
	$data = array();

	$query = $con->prepare("SELECT id, title FROM snippets WHERE title LIKE ? AND user_id = ?");
	$query->bind_param("ss", $text, $user_id);
	$query->execute();
	$query->bind_result($snippetId, $title);
	while($query->fetch()){
		if(!empty($t))
			echo "<div data-snippetId=$snippetId onclick='if (event.target === this) getSnippet($snippetId);' class='snippet'><p onclick='if (event.target === this) getSnippet($snippetId);'>@ $title</p><span onclick='editSnippet($snippetId);'><i class='las la-pencil-square-o'></i></span></div>";
		else
			echo "";
	}
	$query->close();

?>

