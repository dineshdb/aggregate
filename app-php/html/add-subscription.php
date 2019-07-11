<?php

// Initialize the session
session_start();

// Check if the user is already logged in
if(isset($_SESSION["loggedin"]) == false || $_SESSION["loggedin"] === false){
    header("location: login.php");
    exit;
}

// Include config file
require_once "config.php";

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
            $previous = "javascript:history.go(-1)";
			if(isset($_SERVER['HTTP_REFERER'])) {
			    $previous = $_SERVER['HTTP_REFERER'];
			}
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
    <title>Add Subscriptions</title>
    <style type="text/css">
        body{ font: 14px sans-serif;}
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>

<body>
<?php include 'header.php' ?>
    <div class="wrapper">
        
        <h2>Add Subscriptions</h2>
        <p>Add site URL and a title</p>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        
            <div class="form-group <?php echo (!empty($url_err)) ? 'has-error' : ''; ?>">
                <label>URL</label>
                <input type="text" name="url" class="form-control" value="<?php echo $url; ?>">
                <span class="help-block"><?php echo $url_err; ?></span>
            </div>    

            <div class="form-group <?php echo (!empty($title_err)) ? 'has-error' : ''; ?>">
                <label>Title</label>
                <input type="text" name="title" class="form-control"  value="<?php echo $title; ?>">
                <span class="help-block"><?php echo $title_err; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Add">
            </div>

            <p><a href="index.php">Back</a>.</p>
        </form>
    </div>  

</body>
</html>
