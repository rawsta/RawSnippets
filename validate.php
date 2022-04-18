<?php

	session_start();

	include 'database/connect.php';
	include 'langCheck.php';

	$var = "";
	if(empty($_GET['user']) || empty($_GET['code']) || empty($_GET['mail'])){
		$var = $lang['linkNotValid'];
	}else{
		$user_id = $_GET['user'];
		$code = $_GET['code'];
		$mail = $_GET['mail'];
		$query = $con->prepare("SELECT * FROM users WHERE user_id = ? AND code = ?");
		$query->bind_param("ss", $user_id, $code);
		$query->execute();
		$query->store_result();
		$num = $query->num_rows;
		$query->close();

		if($num !== 0){
			$query = $con->prepare("UPDATE users SET email = ? WHERE user_id = ?");
			$query->bind_param("ss", $mail, $user_id);
			$query->execute();
			$query->close();
			$var = $lang['yourEmailIs'].": $mail";
		}else{
			$var = $lang['emailNotChanged'];
		}
	}

?>
<!DOCTYPE html>
<html lang="<?php if(isset($_SESSION['lang'])) { echo $_SESSION['lang']; } else { echo "en"; }?>">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta charset="utf-8">

	<title><?php echo $lang['pageTitle']; ?></title>

	<link rel="icon" href="favicon.ico" sizes="any">
	<link rel="icon" href="favicon.svg" type="image/svg+xml">

	<link rel="stylesheet" href="lib/line-awesome/line-awesome.min.css">
	<link rel="stylesheet" href="lib/sweetalert2/borderless.css">

	<link rel="stylesheet" href="assets/css/fonts.css">
	<link rel="stylesheet" href="assets/css/login.css">

	<script src="lib/jquery/jquery-3.6.0.min.js"></script>
	<script src="lib/sweetalert2/sweetalert2.min.js"></script>

    <script src="assets/js/login.js"></script>

	<meta name="theme-color" content="#830000">
</head>
<body>

	<div class="wrap">
		<p id="display-status"><?php echo $var; ?></p>
		<a href="index.php"><h2 class="main-label"><?php echo $lang['pageTitle']; ?></h2></a>
		<div></div>
		<div></div>
		<div></div>

		<br><br>

		<a id="login-button" class="buttons" href="#"><?php echo $lang['login']; ?></a>
		<a id="register-button" class="buttons" href="register.php"><?php echo $lang['register']; ?></a>
	</div>

	<div class="login-wrap" style="display: none;">
		<a href="index.php"><h1 class="main-label"><?php echo $lang['pageTitle']; ?></h1></a>
		<form action="login-data.php" method="post" class="login-form">
			<input type="text" name="username" placeholder="<?php echo $lang['username']; ?>" id="input-username"><br>
			<input type="password" name="password" placeholder="<?php echo $lang['password']; ?>" id="input-password"><br>
			<input type="checkbox" name="remember-me" value="1"><label><?php echo $lang['rememberMe']; ?></label>
			<a href="#"><?php echo $lang['forgotPassword']; ?></a>
			<input type="submit" value="<?php echo $lang['login']; ?>" id="submit-buttom">
		</form>
	</div>

</body>
</html>
