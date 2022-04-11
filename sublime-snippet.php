<?php

	$code = str_replace('$', '\\$', html_entity_decode($_POST['code']));
	$tabTrigger = $_POST['tabTrigger'];
	$title = $_POST['title'];

	$string = "";
	if(empty($tabTrigger) || !isset($_POST['tabTrigger'])){
		$string = "<snippet>
<content><![CDATA[
$code
]]></content>
</snippet>
";
}else{
	$string = "<snippet>
<content><![CDATA[
$code
]]></content>
<tabTrigger>$tabTrigger</tabTrigger>
</snippet>
";
}
	$filename = strtolower(str_replace(' ', '-', $title)) . ".sublime-snippet";

	header("Content-disposition: attachment; filename=$filename");
	header("Content-type: text/plain");

	echo $string;

?>