<?php

session_start();

// Include config file
require_once "config.php";

if($_SERVER["REQUEST_METHOD"] == "POST"){
	echo "Error";
}

// Get filter parameter and return pages based on those filter parameters.
// Filters: based on host/source, based on username, etc. For now host/source is enough.
