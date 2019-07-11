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

$url = $title = "";
$url_err = $title_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // url and title come in the POST request
    
    if(empty(trim($_POST["url"]))){
        $url_err = "Please enter url.";
    } else{
        $url = trim($_POST["url"]);
    }

    if(empty(trim($_POST["title"]))){
        $title_err = "Please enter title.";
    } else{
        $title = trim($_POST["title"]);
    }

    if(empty($username_err) && empty($password_err)){
        
        $sql = "INSERT INTO pages (url, title) VALUES (?, ?);";
        $stmt = $link->prepare($sql);

        $stmt->bindParam(1, $url);
        $stmt->bindParam(2, $title);

        if ($stmt->execute()){

            // the page was successfully added; now add the subscription
            $param_userId = $_SESSION["id"];
            $param_pageId = $link->lastInsertId();

            $sql = "INSERT INTO subscriptions (userId, pageId) VALUES (?,?);";

            $stmt = $link->prepare($sql);
            $stmt->bindParam(1, $param_userId);
            $stmt->bindParam(2, $param_pageId);

            $stmt->execute();
        } else {
            echo "Could not save values to pages table";
        }

    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    
    <title>Welcome</title>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/js/bootstrap.min.js"></script>
    
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
    <div class="page-header">
        <h1>Hi, <b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>.</h1>
    </div>

    <div class="manage-subscriptions">

        <p>
            <button type="button" class="btn btn-info" data-toggle="modal" 
                    data-target="#add-subscription">Add new Subscription</button>

            <button type="button" class="btn btn-info" data-toggle="modal" 
                    data-target="#manage-subscriptions">Manage Subscriptions</button>

            
            <!-- Modal- add-subscription -->
            <div id="add-subscription" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Add new Subscription</h4>
                    </div>
                    
                    <div class="modal-body">
                        <form class="form-horizontal" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" 
                            style="margin:10px" method="post">

                            <div class="form-group <?php echo (!empty($url_err)) ? 'has-error' : ''; ?>">
                                <input type="text" id="url" name="url" class="form-control" placeholder="Site URL"
                                    value="<?php echo $url; ?>">
                            </div>

                            <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
                                <input type="text" id="title" name="title" class="form-control" placeholder="Site Title"
                                    value="<?php echo $title; ?>">
                            </div>

                            <button type="submit" class="btn btn-default" id="add-button">Add</button>
                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>

                </div>

            </div>
            </div>

            <!-- Modal- manage-subscription -->
            <div id="manage-subscriptions" class="modal fade" role="dialog">
            <div class="modal-dialog">

                <!-- Modal content-->
                <div class="modal-content">
                
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Manage Subscription</h4>
                    </div>
                    
                    <div class="modal-body">
                    
                        <?php

                            $userId = $_SESSION["id"];

                            $sql = "SELECT url, title FROM pages 
                                WHERE pageId IN
                                (SELECT pageId FROM subscriptions 
                                WHERE userId = ?);";

                            $stmt = $link->prepare($sql);
                            $stmt->execute(array($userId));

                            echo " <table class='table'>
                            <thead>
                                <tr>
                                    <th>URL</th>
                                    <th>Title</th>
                                </tr>
                            </thead>";

                            while ($row = $stmt->fetch()) {
                            
                                echo "
                                    <tbody>
                                        <tr>
                                            <td>".$row["url"]."</td>
                                            <td>".$row["title"]."</td>
                                        </tr>   
                                    </tbody>";
                            }
                            
                        ?>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    </div>

                </div>

            </div>
            </div>


        </p>

    </div>

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

