<?php

	session_start();

	include 'database/connect.php';
	include 'functions.php';
	include 'langCheck.php';

	$code = $_GET['code'];
	$user = $_GET['user'];

	if(!$code||!$user){
		$error = $lang['invalidLink'];
	}else{
		if($check = $con->prepare("SELECT * FROM users WHERE username = ? AND code = ?")){
			$check->bind_param("ss", $user, $code);
			$check->execute();
			$check->store_result();
			$number = $check->num_rows;
			$check->close();
		}
		if($check2 = $con->prepare("SELECT active FROM users WHERE username = ?")){
			$check2->bind_param("s", $user);
			$check2->execute();
			$check2->bind_result($result);
			$check2->fetch();
			$check2->close();
		}

		if($result === 1){
			$error = $lang['accountActivated1'];
		}
		else if($number > 0){
			$con->query("UPDATE users SET active = 1");
			$error = $lang['accountActivated2'];
		}
		else{
			$error = $lang['unableToActivate'];
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
    <script type="text/javascript">
		$(document).ready(function(){
			setTimeout(function(){ window.location = 'index.php'; }, 3000);
		});
    </script>
</head>
<body>

<div class="main-wrap" style="text-align: center" >
	<label style="
		background-color: #2980B9;
	    border-radius: 9px;
	    box-shadow: 0 0 10px #2980B9;
	    color: #FFFFFF;
	    display: inline-block;
	    font-family: 'Lato';
	    height: 50px;
	    line-height: 50px;
	    position: relative;
	    top: 100px;
	    width: 300px;
	"><?php echo $error; ?></label>
</div>
</body>
</html>
