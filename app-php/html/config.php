<?php
/* Database credentials */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'php_user');
define('DB_PASSWORD', '1numeric1lower1UPPER1$pecial');
 
/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}

?>