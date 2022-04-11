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

	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,400;0,600;0,800;1,400;1,600;1,800&family=JetBrains+Mono:ital,wght@0,400;0,600;0,800;1,400;1,600;1,800&family=Open+Sans:ital,wght@0,400;0,600;0,800;1,400;1,600;1,800&display=swap" rel="stylesheet">

    <link href="css/main.css" rel="stylesheet" type="text/css">
	<script src="js/jquery-3.6.0.min.js" type="text/javascript"></script>
    <script src="js/script.js" type="text/javascript"></script>
</head>
<body>

<div class="wrap">
	<label id="display-status"><?php echo $var; ?></label>
	<a href="index.php"><label class="main-label"><?php echo $lang['pageTitle']; ?></label></a>
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
