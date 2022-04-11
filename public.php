<?php

	session_start();

	include 'database/connect.php';
	include 'langCheck.php';

	$id = $_GET['id'];

	$query = $con->prepare("SELECT public FROM snippets WHERE id = ?");
	$query->bind_param("s", $id);
	$query->execute();
	$query->bind_result($public);
	$query->fetch();
	$query->close();

	if($public === 1 && isset($_GET['id']) && is_numeric($id)){
		$query = $con->prepare("SELECT snippet FROM snippets WHERE id = ?");
		$query->bind_param("s", $id);
		$query->execute();
		$query->bind_result($code);
		$query->fetch();
		$query->close();

		$code = htmlentities($code);

		$query = $con->prepare("SELECT title, description, date FROM snippets WHERE id = ?");
		$query->bind_param("s", $id);
		$query->execute();
		$query->bind_result($title, $description, $date);
		$query->fetch();
		$query->close();

		$query = $con->prepare("SELECT tags FROM tags_snippets WHERE snippets_id = ?");
		$query->bind_param("s", $id);
		$query->execute();
		$query->bind_result($tag);
		while($query->fetch()){
			$temp[] = "#".$tag;
		}
		$query->close();
		$tagsList = implode(", ", $temp);
?>
<!DOCTYPE html>
<html lang="<?php if(isset($_SESSION['lang'])) { echo $_SESSION['lang']; } else { echo "en"; }?>">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta charset="utf-8">

	<title><?php echo $lang['pageTitle']; ?> - <?php echo $title; ?></title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,400;0,600;0,800;1,400;1,600;1,800&family=JetBrains+Mono:ital,wght@0,400;0,600;0,800;1,400;1,600;1,800&family=Open+Sans:ital,wght@0,400;0,600;0,800;1,400;1,600;1,800&display=swap" rel="stylesheet">

	<link href="css/prettify.css" rel="stylesheet" type="text/css">
    <link href="css/public.css" rel="stylesheet" type="text/css">
    <script src="js/jquery-3.6.0.min.js" type="text/javascript"></script>
    <script src="js/prettify.js"></script>
</head>
<body onload="prettyPrint()">

<main class="window">
    <div class="title-area">
        <a href="index.php"><label title="<?php echo $lang['goHome']; ?>" class="appTitle"><?php echo $lang['pageTitle']; ?></label></a>
    </div>

    <div class="details-window-top">
        <label id="detail-title"><?php echo $title; ?></label>
        <label id="date-label"> - <?php echo $lang['created']; ?> (<?php echo $date; ?>) </label>
    </div>
    <div class="details-window-under">
        <label><?php echo $lang['description']; ?>:</label><br>
        <label id="detail-desc"><?php echo $description; ?></label><br><br>
        <label id="detail-tags"><?php echo $tagsList; ?></label>
    </div>

    <pre class="prettyprint code linenums"><?php echo $code; ?></pre>
</main>

</body>
</html>
<?php }else{ ?>
<!DOCTYPE html>
<html lang="<?php if(isset($_SESSION['lang'])) { echo $_SESSION['lang']; } else { echo "en"; }?>">
<head>
    <meta name="viewport" content="width=device-width,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta charset="utf-8">

	<title><?php echo $lang['pageTitle']; ?> - <?php echo $lang['notFound']; ?></title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:ital,wght@0,400;0,600;0,800;1,400;1,600;1,800&family=JetBrains+Mono:ital,wght@0,400;0,600;0,800;1,400;1,600;1,800&family=Open+Sans:ital,wght@0,400;0,600;0,800;1,400;1,600;1,800&display=swap" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/public.css">
</head>
<body>
<div class="wrap">
	<a href="index.php"><label class="main-label"><?php echo $lang['pageTitle']; ?></label></a>
	<label id="message"><?php echo $lang['snippetNotFound']; ?></label>
</div>

</body>
</html>
<?php } ?>
