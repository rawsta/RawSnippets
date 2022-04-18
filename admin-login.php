<?php
	session_start();
	if(isset($_COOKIE['admin'])){
		$_SESSION['admin'] = $_COOKIE['admin'];
	}
	if(isset($_SESSION['admin'])){
		header("Location: admin-main.php");
	}
	if(isset($_COOKIE['lang'])){
		$_SESSION['lang'] = $_COOKIE['lang'];
	}
	include 'langCheck.php';

	$isLangDE = ( $_SESSION['lang'] == 'de' ) ? 'active' : '';
	$isLangEN = ( $_SESSION['lang'] == 'de' ) ? '' : 'active';
?>
<!DOCTYPE html>
<html lang="<?php if(isset($_SESSION['lang'])) { echo $_SESSION['lang']; } else { echo "en"; }?>">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta charset="utf-8">

	<title><?=$lang['pageTitle']; ?> - <?=$lang['adminPanel']; ?></title>

	<link rel="icon" href="favicon.ico" sizes="any">
	<link rel="icon" href="favicon.svg" type="image/svg+xml">

	<meta name="theme-color" content="#830000">

    <link href="lib/line-awesome/line-awesome.min.css" rel="stylesheet">

    <link href="assets/css/fonts.css" rel="stylesheet">
    <link href="assets/css/admin-main.css" rel="stylesheet">

	<script src="lib/jquery/jquery-3.6.0.min.js"></script>
    <script src="assets/js/admin-script.js"></script>

<body class="login">

	<header class="top">

		<a href="admin-login.php" class="login-title"><h1><?=$lang['adminPanel']?></h1></a>

		<span class="language-wrap">
			<button id="de" class="<?=$isLangDE?>" title="<?=$lang['langDeLong']?>"><i class="las la-beer"></i> </button>
			<button id="en" class="<?=$isLangEN?>" title="<?=$lang['langEnLong']?>"><i class="las la-glass-whiskey"></i> </button>
		</span>

	</header>

	<main class="login-form">
		<div class="login-wrap" >
			<form action="admin-login-data.php" method="post" class="admin-login-form">
				<input type="text" name="username" placeholder="<?=$lang['username']?>" id="admin-input-username"><br>
				<input type="password" name="password" placeholder="<?=$lang['password']?>" id="admin-input-password"><br>
				<input type="checkbox" name="remember-me" value="1" id="input-remember"><label><?=$lang['rememberMe']?></label>
				<a href="#" id="admin-forgot-pass-link"><?=$lang['forgotPassword']?></a>
				<input type="submit" value="<?=$lang['login']; ?>" id="admin-submit-button">
			</form>

			<div class="admin-login-errors">

			</div>
		</div>

		<div class="reset-wrap" style="display: none;">
			<form action="reset-password.php" method="post" class="reset-form">
				<input type="text" name="email" placeholder="<?=$lang['email']; ?>" id="admin-reset-email">
				<input type="submit" value="<?=$lang['submit']; ?>" id="admin-reset-submit">
			</form>

			<div class="admin-reset-errors">
			</div>
		</div>
	</main>
	<footer class="copy">
		<p class="copyright">&prop;&copy;2022&times;&infin; &isin;&ni; &Xi;&equiv; &sub;]|&brvbar;|[&sup; &loz;&Sigma;&sup1; rawsta&trade;&sup2;&sup3; &there4;&radic;&Delta;&forall;&Lambda;&nabla;&nu;&and;&or;</p>
	</footer>
</body>
</html>
