<?php

	session_start();

	include 'database/connect.php';
	include 'functions.php';
	include 'langCheck.php';

	protectAdmin();

	$mail = $_POST['newmail'];
	$repMail = $_POST['repmail'];

	if(empty($mail)){
		echo $lang['emailEmpty'];
		exit();
	}
	if(!filter_var($mail, FILTER_VALIDATE_EMAIL)){
		echo $lang['emailInvalid'];
		exit();
	}
	if($mail != $repMail){
		echo $lang['emailNotMatch'];
		exit();
	}
	
	$query = $con->prepare("update admin set email = ? where username = 'admin'");
	$query->bind_param("s", $mail);
	$query->execute();
	$query->close();

	echo $lang['emailChanged'];
?>