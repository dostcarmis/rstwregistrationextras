<?php

	$serverHost = "localhost";
	$serverUser = "root";
	$serverPass = "car007"; 
	$serverDb = "event_registration";

	$conn = mysqli_connect($serverHost, $serverUser, $serverPass, $serverDb);
	
	if (empty($conn)) {
	    die("mysqli_connect failed: " . mysqli_connect_error());
	}

?>