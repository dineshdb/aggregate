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

if($_SERVER["REQUEST_METHOD"] == "GET"){
    $pageId = $_GET["pageId"];
    $sql = "SELECT url, title, type FROM pages
            WHERE pageId = ?";
    $stmt = $link->prepare($sql);
    $stmt->execute(array($pageId));

    $result = $stmt->fetch();
    $url = $result["url"];
    $title = $result["title"];
    $type = $result["type"];
    
} else if($_SERVER["REQUEST_METHOD"] == "POST"){
    
    $new_url = $_POST["url"];
    $new_title = $_POST["title"];
    $new_type = $_POST["type"];
    $pageId = $_POST["pageId"];
    
    $sql_update = "UPDATE `pages`
            SET `title` = ?, url = ?, type=?
            WHERE `pageId` = ?;";
            
                   
    $stmt_update = $link->prepare($sql_update);
    $stmt_update->bindParam(1, $new_title);
    $stmt_update->bindParam(2, $new_url);
    $stmt_update->bindParam(3, $new_type);
    $stmt_update->bindParam(4, $pageId);
    

    if ($stmt_update->execute()){
        header("location: /manage-subscription.php");
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Subscriptions</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>

<body>
    <div class="wrapper">
        
        <h2>Eidt Subscriptions</h2>
        <p>Eidt site URL and title</p>

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
			<input type="hidden" name="pageId" value="<?php echo $pageId; ?>">
			<select name="type">
				<option value="SITE"  <?php if($type == 'SITE'){echo("selected");}?> >Site</option>
				<option value="RSS" <?php if($type == 'RSS'){echo("selected");}?>>RSS</option>
				<option value="SITEMAP" <?php if($type == 'SITEMAP'){echo("selected");}?>>Sitemap</option>
				<option value="ATOM" <?php if($type == 'ATOM'){echo("selected");}?>>Atom</option>
			</select> 

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Update">
            </div>

            <p><a href="manage-subscription.php">Back</a>.</p>
        </form>
    </div>  

</body>
</html>
