<?php

	session_start();

	include 'functions.php';
	include 'database/connect.php';
	include 'langCheck.php';

	$user = $_POST['username'];
	$pass = $_POST['password'];
	if(isset($_POST['remember-me'])) $rem = $_POST['remember-me'];

	if(empty($user)){
		echo "<label>".$lang['usernameEmpty']."</label>";
		exit();
	}
	if(empty($pass)){
		echo "<label>".$lang['passwordEmpty']."</label>";
		exit();
	}

	if($load = $con->prepare("SELECT * FROM admin WHERE username = ? AND password = ?")){
		$pass = encrypt($_POST['password']);
		$load->bind_param("ss", $user, $pass);
		$load->execute();
		$load->store_result();
		$number = $load->num_rows;
		$load->close();
	}

	if($number === 0){
		echo "<label>".$lang['userPassNotCorrect']."</label>";
		exit();
	}else{
		if($rem === "1"){
			$week = time()+302400;
			setcookie('admin', $user, $week);
		}
		echo "ok";
		$_SESSION['admin'] = $user;
		exit();
	}

?>