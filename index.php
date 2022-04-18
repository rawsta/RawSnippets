<?php
	session_start();

	if( isset( $_COOKIE['user'] ) ) {
		$_SESSION['user'] = $_COOKIE['user'];
	}
	if( isset( $_SESSION['user'] ) ) {
		header("Location: main.php");
	}
	if( isset( $_COOKIE['lang'] ) ) {
		$_SESSION['lang'] = $_COOKIE['lang'];
	}
	include 'langCheck.php';

	$isLangDE = ( $_SESSION['lang'] == 'de' ) ? 'active' : '';
	$isLangEN = ( $_SESSION['lang'] == 'de' ) ? '' : 'active';

?>
<!DOCTYPE html>
<html lang="<?php if(isset($_SESSION['lang'])) echo $_SESSION['lang']; else echo 'en';?>">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title>Identification required ]|[ <?=$lang['pageTitle']?></title>

	<meta name="author" content="Sebastian 'rawsta' Fiele">
	<meta name="description" content="Another Snippet Manager! Under constant construction.">

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

<body class="this-is-still-in-development expect-bugs _and_ surprise-features but_first__lets login">

	<header class="thing-at-athe-top">
		<a href="index.php"><h1 class="appTitle"><i class="las la-file-code"></i> <?=$lang['pageTitle']?></h1></a>

		<span class="language-wrap">
			<button id="de" class="<?=$isLangDE?>" title="<?=$lang['langDeLong']?>"> <i class="las la-beer"></i> </button>
			<button id="en" class="<?=$isLangEN?>" title="<?=$lang['langEnLong']?>"> <i class="las la-glass-whiskey"></i> </button>
		</span>

	</header>

	<main class="index-wrap">

		<article class="tabcordion">
			<!-- TODO: split in seperate forms -->
			<div class="tabcordion--tabs" role="tablist" aria-label="TabCordion">
				<button class="tab is-active" role="tab" aria-selected="true" aria-controls="tab1-tab" id="tab1">LOG-IN</button>
				<button class="tab" role="tab" aria-selected="false" aria-controls="tab2-tab" id="tab2" tabindex="-1">RE-SET</button>
				<button class="tab" role="tab" aria-selected="false" aria-controls="tab3-tab" id="tab3" tabindex="-1">SIGN-UP</button>
			</div>


			<section id="tab1-tab" class="login-wrap tabcordion--entry is-active" data-title="LOG-IN" tabindex="-1" role="tabpanel" aria-labelledby="tab1">
					<div class="login-notice"></div>
				<div class="tabcordion--entry-container">
					<div class="tabcordion--entry-content">
						<!-- LOGIN INTO YOUR FANCY ACCOUNT -->
						<form action="login-data.php" method="post" class="login-form">
							<label for="input-username"><?=$lang['username']?></label>
							<input type="text" name="username" placeholder="<?=$lang['username']?>" id="input-username" autocomplete="username">
							<label for="input-password"><?=$lang['password']?></label>
							<input type="password" name="password" placeholder="<?=$lang['password']?>" id="input-password" autocomplete="current-password">
							<span class="option-group">
								<input type="checkbox" name="remember-me" value="1" id="input-remember"><label for="input-remember"><?=$lang['rememberMe']; ?></label>
								<a href="#" id="forgot-pass-link"><?=$lang['forgotPassword']; ?></a>
							</span>
							<input type="submit" value="<?=$lang['loginPlaceholder']; ?>" class="button large" id="submit-button-login">
						</form>
						<!-- LOGIN FOR TASTY COOKIES -->
					</div>
				</div>
			</section>

			<section id="tab2-tab" class="reset-wrap tabcordion--entry" data-title="RE-SET" tabindex="-1" role="tabpanel" aria-labelledby="tab2">
					<div class="reset-notice"></div>
				<div class="tabcordion--entry-container">
					<div class="tabcordion--entry-content">
						<!-- GET A NEW AND IMPROVED PASSWORD -->
						<form action="reset-password.php" method="post" class="reset-form">
							<label for="reset-email"><?=$lang['emailPlaceholder']?></label>
							<input type="email" name="email" placeholder="<?=$lang['emailPlaceholder']?>" id="reset-email">
							<input type="submit" value="<?=$lang['resetSubmit']; ?>" class="button large" id="reset-submit">
						</form>
						<!-- NOW WITHOUT SALT AND HASH! -->
					</div>
				</div>
			</section>

			<section id="tab3-tab" class="register-wrap tabcordion--entry" data-title="SIGN-UP" tabindex="-1" role="tabpanel" aria-labelledby="tab3">
					<div class="register-notice"></div>
				<div class="tabcordion--entry-container">
					<div class="tabcordion--entry-content">
						<!-- REQUEST TO JOIN ONE OF THE MOST EXCLUSIVE CLUBS IN THE WORLD!!! -->
						<form action="register-data.php" method="post" class="register-form">
							<label for="username-register"><?=$lang['username']?></label>
							<input type="text" name="username" placeholder="<?=$lang['username']?>" id="username-register">
							<label for="email-register"><?=$lang['email']?></label>
							<input type="email" name="email" placeholder="<?=$lang['email']?>" id="email-register">
							<label for="password-register"><?=$lang['password']?></label>
							<input type="password"
									name="password"
									placeholder="<?=$lang['password']?>"
									id="password-register"
									minlength="8"
									autocomplete="new-password">
							<label for="rpassword-register"><?=$lang['repeatPassword']?></label>
							<input type="password"
									name="rpassword"
									placeholder="<?=$lang['repeatPassword']?>"
									id="rpassword-register"
									minlength="8"
									autocomplete="new-password">
							<input type="submit" value="<?=$lang['registerSubmit']; ?>" id="submit-register" disabled>
						</form>
						<!-- LOGIN FOR TASTY COOKIES -->
					</div>
				</div>
			</section>

		</article>
	</main>
	<footer class="info">
			<p class="copyright"><a href="//www.rawsta.de/" class="link-to-rawsta" >&copy;2022 | RawSnippets&trade;</a> ]|&brvbar;|[ <a href="https://github.com/rawsta/RawSnippets" class="link-to-github" >blame rawsta for this.</a></p>
	</footer>
</body>
</html>
