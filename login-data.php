<?php

	session_start();

	include 'functions.php';
	include 'database/connect.php';
	include 'langCheck.php';

	$user = $_POST['username'];
	$pass = $_POST['password'];
	if( isset( $_POST['remember-me'] ) ) $rem = $_POST['remember-me'];

	if( empty( $user ) ) {
		echo "<span class='error'>" . $lang['usernameEmpty']. "</span>";
		exit();
	}
	if( empty( $pass ) ) {
		echo "<span class='error'>" . $lang['passwordEmpty']. "</span>";
		exit();
	}

	if( $load = $con->prepare( "SELECT * FROM users WHERE username = ? AND password = ?" ) ) {
		$pass = encrypt( $_POST['password'] );
		$load->bind_param( "ss", $user, $pass );
		$load->execute();
		$load->store_result();
		$number = $load->num_rows;
		$load->close();
	}

	if( $number === 0 ) {
		echo "<span class='error'>" . $lang['userPassNotCorrect'] . "</span>";
		exit();
	} else {
		if( $active = $con->prepare("SELECT active, banned FROM users WHERE username = ?" ) ) {
			$active->bind_param( "s", $user );
			$active->execute();
			$active->bind_result( $res, $banned );
			$active->fetch();
		}

		if( $res === 1 && $banned === 0 ) {
			if( $rem === "1" ) {
				$week = time()+302400;
				setcookie( 'user', $user, $week );
			}
			echo "ok";
			$_SESSION['user'] = $user;
			exit();
		} else {
			if( $banned === 1 ) {
				echo "<span class='error'>" . $lang['bannedAccount']. "</span>";
				exit();
			}
			echo "<span class='error'>" . $lang['notActivated']. "</span>";
			exit();
		}
	}
?>