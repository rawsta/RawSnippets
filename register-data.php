<?php

	session_start();

	include 'database/connect.php';
	include 'functions.php';
	include 'langCheck.php';

	const SPAN1 = "<span>";
	const SPAN2 = "</span>";

	$username = $_POST['username'];
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$rpassword = $_POST['rpassword'];

	$userCheck = $con->prepare("SELECT * FROM users WHERE username = ?");
	$userCheck->bind_param("s", $username);
	$userCheck->execute();
	$userCheck->store_result();
	$nmb = $userCheck->num_rows;
	$userCheck->close();

	$mailCheck = $con->prepare("SELECT * FROM users WHERE email = ?");
	$mailCheck->bind_param("s", $email);
	$mailCheck->execute();
	$mailCheck->store_result();
	$mm = $mailCheck->num_rows;
	$mailCheck->close();

	$check = 0;



	if( $nmb > 0 ) {
		$errors[] = SPAN1.$lang['userExists'].SPAN2;
		$check = 1;
	}
	if( $mm > 0 ) {
		$errors[] = SPAN1.$lang['mailExists'].SPAN2;
		$check = 1;
	}
	if( empty($username) ) {
		$errors[] = SPAN1.$lang['usernameEmpty'].SPAN2;
		$check = 1;
	}
	if( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {
		$errors[] = SPAN1.$lang['emailInvalid'].SPAN2;
		$check = 1;
	}
	if( empty($password) ) {
		$errors[] = SPAN1.$lang['passwordEmpty'].SPAN2;
		$check = 1;
	}
	if( $password !== $rpassword ) {
		$errors[] = SPAN1.$lang['passwordInvalid'].SPAN2;
		$check = 1;
	}
	if( $check === 0 ) {
		$password = encrypt($password);
		$date = date('Y-m-d');

		/* TODO: add text to lang.php */
		// ***send mail for validation***
		$code = uniqid();
		$to = $email; // email will be sent to this address
		$subject = "Activate your account"; //email subject
		$headers = "From: $yourEmail"; //this is your mail
		$body = "Hello $user\n\nYou registered and need to activate your mail.
Please click on following link or paste it in your browser:\n\n
$pageRoot/activate.php?user=$username&code=$code\n\n
Thank you!";

		if( !mail($to, $subject, $body, $headers) ) {
			$errors[] = SPAN1.$lang['mailError'].SPAN2;
		} else {

			if( $std = $con->prepare("INSERT INTO users (username, password, email, code, joined) VALUES(?,?,?,?,?)") ) {
				$std->bind_param("sssss", $username, $password, $email, $code, $date);
				$std->execute();
				$std->close();
			}
			$errors[] = SPAN1.$lang['successRegister'].SPAN2;
		}
	}
	echo json_encode($errors);
?>
