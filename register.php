<?php session_start();?>
<?php
	include 'langCheck.php';
?>
<!DOCTYPE html>
<html lang="<?php if(isset($_SESSION['lang'])) { echo $_SESSION['lang']; } else { echo "en"; }?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo $lang['pageTitle']; ?> &mdash; <?php echo $lang['registerPage']; ?></title>

    <link href="assets/css/fonts.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">

	<script src="lib/jquery/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js"></script>
</head>
<body>

<div class="register-wrap">
	<a href="index.php"><h1 class="main-label">WRONG! -> GO TO INDEX</h1></a>
	<a href="index.php"><label class="main-label"><?php echo $lang['pageTitle']; ?></label></a>
	<form action="register-data.php" method="post" class="register-form">
		<input type="text" name="username" placeholder="<?php echo $lang['username']; ?>" id="username-register"><br>
		<input type="text" name="email" placeholder="<?php echo $lang['email']; ?>" id="email-register"><br>
		<input type="password" name="password" placeholder="<?php echo $lang['password']; ?>" id="password-register"><br>
		<input type="password" name="rpassword" placeholder="<?php echo $lang['repeatPassword']; ?>" id="rpassword-register"><br>
		<input type="submit" value="<?php echo $lang['registerSubmit']; ?>" id="submit-button">
	</form><br>

	<div class="error"></div>
</div>
</body>
