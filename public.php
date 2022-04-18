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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?php echo $lang['pageTitle']; ?> - <?php echo $title; ?></title>

    <link rel="stylesheet" href="assets/css/fonts.css">
    <link rel="stylesheet" href="assets/css/public.css">

    <script src="lib/jquery/jquery-3.6.0.min.js"></script>
    <script src="lib/prism/prism.js"></script>
</head>
<body>

	<main class="window">
		<div class="title-area">
			<a href="index.php"><h2 title="<?php echo $lang['goHome']; ?>" class="appTitle"><?php echo $lang['pageTitle']; ?></h2></a>
		</div>

		<div class="details-window-top">
			<h3 id="detail-title"><?php echo $title; ?></h3>
			<span id="date-label"> - <?php echo $lang['created']; ?> (<?php echo $date; ?>) </span>
		</div>
		<div class="details-window-under">
			<span><?php echo $lang['description']; ?>:</span><br>
			<span id="detail-desc"><?php echo $description; ?></span><br><br>
			<span id="detail-tags"><?php echo $tagsList; ?></span>
		</div>

		<pre class="pre-code <?=$linenumbers?> language-<?=$date; ?> rainbow-braces">
			<code id="code-block" class="language-<?=$date; ?>">
				<?=$code?>
			</code>
		</pre>
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

    <link rel="stylesheet" href="assets/css/fonts.css">
    <link rel="stylesheet" href="assets/css/public.css">

    <script src="lib/jquery/jquery-3.6.0.min.js"></script>
    <script src="lib/prism/prism.js"></script>
</head>
<body>
	<div class="wrap">
		<a href="index.php"><h2 class="main-label"><?php echo $lang['pageTitle']; ?></h2></a>
		<p id="message"><?php echo $lang['snippetNotFound']; ?></p>
	</div>

</body>
</html>
<?php } ?>
