<?php

	session_start();

	include 'database/connect.php';
	include 'functions.php';
	include 'langCheck.php';

	$email = $_POST['email'];
	$flag = $_POST['flag'];

	if($flag == 'user'){
		if(empty($email)){
			echo $lang['emailEmpty'];
			exit();
		}

		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			echo $lang['emailInvalid'];
			exit();
		}

		$query = $con->prepare("SELECT * FROM users WHERE email = ?");
		$query->bind_param("s", $email);
		$query->execute();
		$query->store_result();
		$nm = $query->num_rows;
		$query->close();

		if($nm == 0){
			echo $lang['userWithMailNotExists'];
			exit();

		} else {

			/* TODO: add text to lang.php */
			$code = uniqid();
			$to = $email;
			$subject = "Password reset"; //email subject
			$headers = "From: $yourEmail"; //this is your mail
			$body = "Hello\n\nYou requested password reset!\n\n
Your new password is:\n\n
$code\n\n
Make sure to change password as soon as you login.\n
Thank you";

			if(!mail($to, $subject, $body, $headers)){
				echo $lang['mailError'];
				exit();
			}
			else{
				$pass = encrypt($code);
				if($std = $con->prepare("UPDATE users SET password = ? WHERE email = ?")){
					$std->bind_param("ss", $pass, $email);
					$std->execute();
					$std->close();
				}
				echo $lang['checkEmail'];
				exit();
			}
		}
	}else if($flag == 'admin'){
		if(empty($email)){
			echo $lang['emailEmpty'];
			exit();
		}

		if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			echo $lang['emailInvalid'];
			exit();
		}

		$query = $con->prepare("SELECT * FROM admin WHERE email = ?");
		$query->bind_param("s", $email);
		$query->execute();
		$query->store_result();
		$nm = $query->num_rows;
		$query->close();

		if( $nm == 0 ) {
			echo $lang['adminWithMailNotExists'];
			exit();

		} else {

			/* TODO: add text to lang.php */
			$code = uniqid();
			$to = $email;
			$subject = "Password reset"; //email subject
			$headers = "From: $yourEmail"; //this is your mail
			$body = "Hello Admin\n\nYou forgot your password again!\n\n
Your new password is:\n\n
$code\n\n
Make sure to change password as soon as you login.\n
Thank you";

			if( !mail($to, $subject, $body, $headers) ) {
				echo $lang['mailError'];
				exit();
			} else {
				$pass = encrypt($code);
				if($std = $con->prepare("UPDATE admin SET password = ? WHERE email = ?")){
					$std->bind_param("ss", $pass, $email);
					$std->execute();
					$std->close();
				}
				echo $lang['checkEmail'];
				exit();
			}
		}
	}

?>