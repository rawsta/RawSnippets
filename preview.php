<?php

	session_start();

	include 'database/connect.php';
	include 'functions.php';

	protectAdmin();
	include 'langCheck.php';

	$id = $_GET['id'];

	$query = $con->prepare( "SELECT * FROM snippets WHERE id = ?" );
	$query->bind_param( "s", $id);
	$query->execute();
	$query->store_result();
	$number = $query->num_rows;
	$query->close();

	if( $number>0 && isset($_GET['id']) && is_numeric($id) ) {
		$query = $con->prepare( "SELECT snippet FROM snippets WHERE id = ?" );
		$query->bind_param( "s", $id);
		$query->execute();
		$query->bind_result($code);
		$query->fetch();
		$query->close();

		$code = htmlentities($code);

		$query = $con->prepare( "SELECT title, syntax, description, date FROM snippets WHERE id = ?" );
		$query->bind_param( "s", $id);
		$query->execute();
		$query->bind_result($title, $syntax, $description, $date);
		$query->fetch();
		$query->close();

		$query = $con->prepare( "SELECT tags FROM tags_snippets WHERE snippets_id = ?" );
		$query->bind_param( "s", $id);
		$query->execute();
		$query->bind_result($tag);
		while($query->fetch()){
			$temp[] = "#".$tag;
		}
		$query->close();
		$tagsList = implode( ", ", $temp);
?>
<!DOCTYPE html>
<html lang="<?php if(isset($_SESSION['lang'])) { echo $_SESSION['lang']; } else { echo "en"; }?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

	<title><?=$lang['pageTitle']; ?> - <?=$title; ?></title>

    <link rel="stylesheet" href="assets/css/fonts.css">
    <link rel="stylesheet" href="assets/css/public.css">

    <script src="lib/jquery/jquery-3.6.0.min.js"></script>
    <script src="lib/prism/prism.js"></script>
</head>
<body>

<main class="main-wrap">
    <div class="title-area">
        <a href="admin-main.php"><label title="<?=$lang['goHome']?>" class="appTitle"><?=$lang['pageTitle']; ?></label></a>
    </div>

    <div class="details-window-top">
        <h3 id="detail-title"><?=$title?></h3>
        <span id="date-label"> - <?=$lang['created']?> (<?=$date?>) </span>
    </div>
    <div class="details-window-under">
        <span><?=$lang['description']?>:</span><br>
        <span id="detail-desc"><?=$description?></span><br><br>
        <span id="detail-tags"><?=$tagsList?></span>
    </div>

    <pre class="pre-code <?=$linenumbers?> language-<?=$date; ?> rainbow-braces">
		<code id="code-block" class="language-<?=$date; ?>">
			<?=$code?>
		</code>
	</pre>
</main>

</body>
</html>

<?php } else { ?>

<!DOCTYPE html>
<html lang="<?php if(isset($_SESSION['lang'])) { echo $_SESSION['lang']; } else { echo "en"; }?>">
<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,initial-scale=1">

	<title><?=$lang['pageTitle']; ?> - <?=$lang['notFound']; ?></title>

	<link rel="stylesheet" href="assets/css/fonts.css">
    <link rel="stylesheet" href="assets/css/public.css">

    <script src="lib/jquery/jquery-3.6.0.min.js"></script>
    <script src="lib/prism/prism.js"></script>
</head>
<body>
	<main class="main-wrap">
		<a href="admin-main.php"><h2 class="main-label"><?=$lang['pageTitle']; ?></h2></a>
		<p id="message"><?=$lang['snippetNotFound']; ?></p>
	</main>
</body>
</html>
<?php } ?>
