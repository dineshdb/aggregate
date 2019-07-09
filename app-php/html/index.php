<?php
// phpinfo();
// Initialize the session
session_start();

// Include config file
require_once "config.php";

// check if the database exists
$create_database_query = "CREATE DATABASE IF NOT EXISTS php_db;";

$table_exists_query = "SELECT COUNT(*)
    FROM information_schema.tables 
    WHERE table_schema = 'users' 
    AND table_name = 'php_db';";

$create_table_query = "CREATE TABLE users (
    id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);";

// the database gets created even if it does not exist
if (mysqli_query($link, $create_database_query)){
    
    // check if the table exists in the database
    if ($stmt = mysqli_prepare($link, $table_exists_query)){

        // attempt to execute the prepared statement
        if (mysqli_stmt_execute($stmt)){

            // store the result
            mysqli_stmt_store_result($stmt);

            // check if table does not exist
            if(mysqli_stmt_num_rows($stmt) < 1){
                
                // create the table
                if (mysqli_query($link, $create_table_query)){

                    // table created successfully
                    // maybe do a console.log here to output something here
                }
            } 
            
            // the table already exists so do nothing
        }
    }
}
 
// Check if the user is logged in, if not then redirect him to login page
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
<body>
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Welcome to our site.</h1>
    </div>
    <p>
        <a href="reset-password.php" class="btn btn-warning">Reset Your Password</a>
        <a href="logout.php" class="btn btn-danger">Sign Out of Your Account</a>
    </p>
</body>
</html>