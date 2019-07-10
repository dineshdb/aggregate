<?php
/* Database credentials */
$servername = 'database';
$username = 'aggregator';
$password = 'aggregator';

try {
    /* Attempt to connect to MySQL database */
    $link = new PDO("mysql:host=$servername;dbname=db", $username, $password);
    $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e){
    echo "Connection failed: " . $e->getMessage();
    die();
}

?>