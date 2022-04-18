<?php
	/* get language from POST */
	$lang = $_POST['lang'];
	/* extend cookie time to +1 month */
	$expire = time() + 60 * 60 * 24 * 30;
	setcookie("lang", $lang, $expire);
	/* set cookie and exit */
	exit();
?>