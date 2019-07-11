<?php

// $servername = "database";
// $username = "aggregator";
// $password = "aggregator";


// // Create connection
// $conn = new pdo("mysql:host=$servername;dbname=db", $username, $password);

// // Check connection
// if ($conn->connect_error) {
//     die("Connection failed: " . $conn->connect_error);
// }
// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//    // Do api works here
// } else {
// 	// render the html ui here
// }


// Initialize the session
session_start();

// Include config file
require_once "config.php";

// check if the database exists
$create_database_query = "CREATE DATABASE IF NOT EXISTS db;";

$table_exists_query = "SELECT COUNT(*)
    FROM information_schema.tables 
    WHERE table_schema = 'db' 
    AND table_name = 'users';";

$users_create_table_query = "CREATE TABLE IF NOT EXISTS users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);";

$subscriptions_create_table_query = "CREATE TABLE IF NOT EXISTS subscriptions (
    subId INT NOT NULL PRIMARY KEY AUTO_INCREMENT, 
    userId INT NOT NULL, 
    pageId INT NOT NULL
    -- FOREIGN KEY (userId) REFERENCES users(id),
    -- FOREIGN KEY (pageId) REFERENCES pages(pageId)
);";
// intentionally omitting the cascade due to updates and deletes

$pages_create_table_query = "CREATE TABLE IF NOT EXISTS pages (
    pageId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    lastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    url VARCHAR(2083),
    title VARCHAR(255)
);";

$stmt = $link->query($table_exists_query);

if ($stmt->fetchColumn() > 0){
    
} else {
    $link->query($users_create_table_query);
}

$link->query($pages_create_table_query);
$link->query($subscriptions_create_table_query);

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    
    <title>Welcome</title>
    
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
        
        * { box-sizing: border-box; }

        /* Create two equal columns that floats next to each other */
        .column {
        float: left;
        width: 50%;
        padding: 10px;
        height: 500px; /* Should be removed. Only for demonstration */
        }

        /* Clear floats after the columns */
        .row:after {
        content: "";
        display: table;
        clear: both;
        }

        /* Responsive layout - makes the two columns stack on top of each other instead of next to each other */
        @media screen and (max-width: 600px) {
        .column {
            width: 100%;
        }
        }

        .page-footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            text-align: center;
        }

    </style>

</head>
<body>
<?php include 'header.php' ?>

    <div class="row">

        <!-- this column contains the page links -->
        <div class="column">
            hola
        </div>

        <!-- this column contains the iframe elements -->
        <div class="column">
            <iframe src="" name="page_display"></iframe>
        </div>

    </div>
    <div class="page-footer">
        <p>        
            <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
            <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
        </p>
    </div>
</body>
</html>

