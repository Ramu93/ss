<?php

$type = "else";

if($type == "local") {
	$db_servername = 'localhost';
	$db_username = 'root';
	$db_password = 'root';
	$db_name = 'acs';
}
else {
	$db_servername = 'localhost';
	$db_username = 'shreefas_acsuser';
	$db_password = 'acs123';
	$db_name = 'shreefas_sbbs';
}

// Connect to MySQL:
$dbc = mysqli_connect($db_servername,$db_username,$db_password,$db_name);

// Confirm the connection and select the database:
if (mysqli_connect_errno()) {
    echo "Could not establish database connection!<br>";
    exit();
}

?>