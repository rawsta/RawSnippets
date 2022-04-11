<?php

	$host = 'localhost';
	$username = 'DBUSER';
	$password = 'DBPASS';
	$database = 'DBNAME';

	$pageRoot = 'https://URL.TO/RAW-SNIPPETS'; // without " / " at the end
	$yourEmail = 'YOUR@EMAIL.XY';
	$numUsers = 20; // number of users per page in admin panel
	$numSnip = 20; // number of snippets per page in admin panel

	$con = new mysqli($host, $username, $password, $database);

	if (mysqli_connect_errno()) {
	    printf("Connect failed: %s\n", "Server error");
	    exit();
	}

?>
