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

$create_table_query = "CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);";


$stmt = $link->query($table_exists_query);

if ($stmt->fetchColumn() > 0){

} else {
    $link->query($create_table_query);
}


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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>

<body onload="showRSS(document.getElementById('selected-site').value)">
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    </div>

    <form>
        <select onchange="showRSS(this.value)" id="selected-site">
            <option value="">Select an RSS-feed:</option>
            <option value="r/programming">r/programming</option>
            <option value="Ycombinator">Hacker News</option>
            <option value="LWN">LWN.net</option>
            <option value="ZDN">ZDNet News</option>
            <option value="Coding Horror">Coding Horror</option>
            <option value="Google">Google News</option>
        </select>
    </form>
    
    <br>
    <div id="rssOutput">RSS-feed will be listed here...</div>
    <br>

    <p>
        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>

    <script>
        function showRSS(str) {
            if (str.length == 0) { 
                document.getElementById("rssOutput").innerHTML = "";
                return;
            }

            if (window.XMLHttpRequest) {
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp = new XMLHttpRequest();
            } else {  // code for IE6, IE5
                xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
            }
            
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                document.getElementById("rssOutput").innerHTML = this.responseText;
                }
            }

            xmlhttp.open("GET", "getRSS.php?q=" + str,true);
            xmlhttp.send();
        }
    </script>
</body>
</html>

