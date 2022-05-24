<?php
	ob_start();
	$errors = array();

	function protect() {
		// if no Session and Cookie is found - redirect to login
		if( !isset( $_SESSION['user'] ) && !isset( $_COOKIE['user'] ) ) {
			header( "Location: index.php" );
			exit();
		}
	}

	function protectAdmin() {
		// if no Session and Cookie is found - redirect to login
		if( !isset( $_SESSION['admin'] ) && !isset( $_COOKIE['admin'] ) ) {
			header( "Location: admin-login.php" );
			exit();
		}
	}

	/**
	 * "encrypt" passwords.
	 *
	 * @param  [type] $pass
	 * @return [type] $pass
	 */
	function encrypt( $pass ) {
		/* TODO: add Salt to .env && maybe add hash*/
		$salt = "jhkl2jh8f8s898we8ewiouq48484b";
		return sha1( $salt . $pass );
	}


	/**
	 * Count User
	 *
	 * @return int $userCount
	 */
	function user_counter() {
		include 'database/connect.php';

		// session_start();

		$session = session_id();
		$time = time();
		$time_check = $time-600;

		if( session_id() !== '' && $session !== '' ) {

			$query = $con->prepare("SELECT * FROM user_online WHERE session = ?");
			$query->bind_param("s", $session);
			$query->execute();
			$query->store_result();
			$nm = $query->num_rows;
			$query->close();

			if( $nm == '0' ) {
				$query = $con->prepare("INSERT INTO user_online (session, time) VALUES(?, ?)");
				$query->bind_param("ss", $session, $time);
				$query->execute();
				$query->close();
			} else {
				$query = $con->prepare("UPDATE user_online SET time = ? WHERE session = ?");
				$query->bind_param("ss", $time, $session);
				$query->execute();
				$query->close();
			}
		}

		$query = $con->prepare("DELETE FROM user_online WHERE time < ?");
		$query->bind_param("s", $time_check);
		$query->execute();
		$query->close();

		$query = $con->prepare("SELECT count(*) FROM user_online");
		$query->bind_result($userCount);
		$query->execute();
		$query->fetch();
		$query->close();

		return $userCount;
	}
?>
