<?php

session_start();

// Check if the user is logged in
if(isset($_SESSION["loggedin"]) == false || $_SESSION["loggedin"] === false){
    header("location: login.php");
    exit;
}

require_once "config.php";

if($_SERVER["REQUEST_METHOD"] == "GET"){

    $pageId = $_GET["pageId"];

    $pgs_sql = "DELETE FROM pages 
            WHERE pageId = ?";
    $subs_sql = "DELETE FROM subscriptions
                WHERE pageId = ?";

    $stmt = $link->prepare($pgs_sql);
    $stmt->execute(array($pageId));

    $stmt = $link->prepare($subs_sql);
    if ($stmt->execute(array($pageId))){
        header("location: manage-subscription.php");
    }
}

?>