<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo $lang['pageTitle']; ?> &mdash; 404</title>

    <link href="assets/css/fonts.css" rel="stylesheet">
    <link href="assets/css/main.css" rel="stylesheet">

	<script src="lib/jquery/jquery-3.6.0.min.js"></script>
    <script src="assets/js/script.js"></script>
</head>
<body>

<div class="login-wrap">
	<a href="index.php"><h1 class="main-label">WRONG! -> GO TO INDEX</h1></a>
	<a href="index.php"><h2 class="main-label">RawSnippets</h2></a>
	<form action="login-data.php" method="post" class="login-form" disabled>
		<input type="text" name="username" placeholder="Username" id="input-username"><br>
		<input type="password" name="password" placeholder="Password" id="input-password"><br>
		<input type="checkbox" name="remember-me" value="1"><label>Remember me</label>
		<a href="#">Forgot password?</a>
		<input type="submit" value="Login" id="submit-buttom" disabled>
	</form>
</div>

</body>
</html>
