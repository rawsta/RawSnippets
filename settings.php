<?php

	session_start();

	include 'database/connect.php';
	include 'functions.php';
	include 'langCheck.php';

	protect();

	$query = $con->prepare( "SELECT user_id FROM users WHERE username = ?" );
    $query->bind_param( "s", $_SESSION['user']);
    $query->execute();
    $query->bind_result( $user_id);
    $query->fetch();
    $query->close();

	if( !empty( $_POST['set'] ) ) {
		$set = $_POST['set'];

		if( $set === '1' ) { // page 1 - main

			$lineNums = $_POST['line-nums'];
			$font = $_POST['font'];
			$size = $_POST['size'];

			$query = $con->prepare( "UPDATE users SET line_nums = ?, font = ?, size = ? WHERE user_id = ?" );
			$query->bind_param( "ssss", $lineNums, $font, $size, $user_id);
			$query->execute();
			$query->close();
			echo 'ok';
			exit();

		} else if( $set === '2' ) { // page 2 - change email

			$mail = $_POST['new-mail'];
			$repMail = $_POST['rep-mail'];

			if( empty( $mail ) ) {
				echo $lang['emailEmpty'];
				exit();
			}
			if( !filter_var( $mail, FILTER_VALIDATE_EMAIL ) ) {
				echo $lang['emailInvalid'];
				exit();
			}
			if( $mail !== $repMail ) {
				echo $lang['emailNotMatch'];
				exit();
			}

			$code = uniqid();
			/* TODO: add text to lang.php */
			$to = $mail;
			$subject = "Confirm your email"; //email subject
			$headers = "From: $yourEmail";
			$body = "Hello\n\n
You requested a change of your email address. To complete this process\n
please click on following link or paste it in your browser:\n\n
$pageRoot/validate.php?user=$user_id&code=$code&mail=$mail\n\n
Thank you!";

			if( !mail( $to, $subject, $body, $headers ) ) {
				echo $lang['mailError'];
				exit();
			}
			$query = $con->prepare( "UPDATE users SET code = ? WHERE user_id = ?" );
			$query->bind_param( "ss", $code, $user_id);
			$query->execute();
			$query->close();

			echo 'ok';

			exit();

		} else if( $set === '3' ) { // page 3 - change password

			$oldPass = $_POST['old-pass'];
			$newPass = $_POST['new-pass'];
			$repPass = $_POST['rep-pass'];

			if( empty( $oldPass ) || empty( $newPass ) || empty( $repPass ) ) {
				echo $lang['fillAllFields'];
				exit();
			}
			$pass = encrypt( $oldPass );
			$query = $con->prepare( "SELECT * FROM users WHERE user_id = ? AND password = ?" );
			$query->bind_param( "ss", $user_id, $pass );
			$query->execute();
			$query->store_result();
			$num = $query->num_rows;
			$query->close();

			if( $num === 0) {
				echo $lang['passwordInvalid'];
				exit();
			}
			if( $newPass !== $repPass ) {
				echo $lang['passwordsNotMatch'];
				exit();
			}
			$pass = encrypt( $newPass );
			$query = $con->prepare( "UPDATE users SET password = ? WHERE user_id = ?" );
			$query->bind_param( "ss", $pass, $user_id );
			$query->execute();
			$query->close();
			echo 'ok';

			exit();
		}
	}

?>
