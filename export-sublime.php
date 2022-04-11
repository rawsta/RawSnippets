<?php

	session_start();
	include 'database/connect.php';
	include 'functions.php';
	include 'langCheck.php';

	protect();

	$query = $con->prepare("SELECT user_id FROM users WHERE username = ?");
	$query->bind_param("s", $_SESSION['user']);
	$query->execute();
	$query->bind_result($user_id);
	$query->fetch();
	$query->close();

	$data = array();

	$query = $con->prepare("SELECT title, snippet FROM snippets WHERE user_id = ?");
	$query->bind_param("s", $user_id);
	$query->execute();
	$query->bind_result($title, $snippet);
	while($query->fetch()){
		$data['title'][] = $title;
		$data['snippet'][] = $snippet;
	}
	$query->close();
	$string = "";

	if (!file_exists($_SESSION['user'])) {
		mkdir($_SESSION['user'], 0755, true);
	}
	$files = array();

	for($i = 0; $i < count($data['title']); $i++){
		$code = $data['snippet'];
		$title = $data['title'];
		$string = "<snippet>
<content><![CDATA[".
str_replace('$', '\\$', html_entity_decode($code[$i]))."
]]></content>
</snippet>
";

	file_put_contents($_SESSION['user'] ."/". $title[$i] . ".sublime-snippet", $string);
	$files[] =  $title[$i] . ".sublime-snippet";
	}
	$inst = $lang['installationInst'];
	file_put_contents($_SESSION['user'] ."/". $lang['instructions']. ".txt", $inst);
	$zip = new ZipArchive();
	$fileName = uniqid() . ".zip";
	$zip->open($fileName, ZipArchive::CREATE);
	foreach($files as $file){
		$zip->addFile($_SESSION['user'] ."/". $file, "snippets/$file");
	}
	$zip->addFile($_SESSION['user'] ."/".$lang['instructions'].".txt", $lang['instructions'].".txt");
	$zip->close();

	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: public");
	header("Content-Description: File Transfer");
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=\"".$fileName."\"");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".filesize($fileName));
	ob_end_flush();
	@readfile($fileName);

	foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($_SESSION['user'], FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $path) {
		$path->isFile() ? unlink( $path->getPathname() ) : rmdir($path->getPathname());
	}
	rmdir($_SESSION['user']);
	unlink($fileName);

?>