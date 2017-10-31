<?php

// Database access

$mysqli = new mysqli('localhost', 'root', 'fuzzykittens', 'calendardb');
 
if($mysqli->connect_errno) {
	printf("Connection Failed: %s\n", $mysqli->connect_error);
	exit;
}
?>