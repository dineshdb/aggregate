<?php

session_start();

// Check if the user is logged in
if(isset($_SESSION["loggedin"]) == false || $_SESSION["loggedin"] === false){
    header("location: login.php");
    exit;
}

require_once "config.php";

if($_SERVER["REQUEST_METHOD"] == "GET"){

    $userId = $_SESSION["id"];

    $sql = "SELECT url, title, pageId, type FROM pages 
        WHERE pageId IN
        (SELECT pageId FROM subscriptions 
        WHERE userId = ?);";

    $stmt = $link->prepare($sql);
    $stmt->execute(array($userId));

    $result = $stmt->fetchAll();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Subscriptions</title>
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ padding: 20px; }
    </style>
</head>

<body>

<?php include 'header.php' ?>
    <div class="wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>URL</th>
                    <th>Type</th>
                    <th>Options</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($result as $row) { ?>
                <tr>
                    <td> <?php echo $row["title"]; ?></td>
                    <td> <?php echo $row["url"]; ?></td>
                    <td> <?php echo $row["type"]; ?></td>
                    <td> 
                        <p>

                            <a href="edit-subscription.php?pageId=<?php echo $row["pageId"]; ?>">
                                Edit</a>
                            <a href="delete-subscription.php?pageId=<?php echo $row["pageId"]; ?>">
                                Delete</a>
                        </p>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <a href="index.php">Back</a>
    </div> 

</body>
</html>
