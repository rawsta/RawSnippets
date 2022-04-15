<?php

	session_start();

	unset( $_SESSION['user'] );
	$week = time()-302400; // cookie is set for one week, if you change this value,
	                       // make sure you change it in login-data.php too
	setcookie('user', $user, $week);

	// TODO: session start again? reset or new session? confirm or fix this
	include 'database/connect.php';
	session_start();
	$session = session_id();

	$query = $con->prepare("DELETE FROM user_online WHERE session = ?");
	$query->bind_param("s", $session);
	$query->execute();
	$query->close();

	header("Location: index.php");

?>