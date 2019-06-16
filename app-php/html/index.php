<?php
$servername = "database";
$username = "aggregator";
$password = "aggregator";


// Create connection
$conn = new pdo("mysql:host=$servername;dbname=db", $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   // Do api works here
} else {
	// render the html ui here
}
?> 


