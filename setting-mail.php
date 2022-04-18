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
	/* TODO: fix the need for hardcoded user */
	$query = $con->prepare("UPDATE admin SET email = ? WHERE username = 'rawsta'");
	$query->bind_param("s", $mail);
	$query->execute();
	$query->close();

	echo $lang['emailChanged'];
?>