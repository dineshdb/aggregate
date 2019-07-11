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

    $sql = "SELECT url, title, pageId FROM pages 
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
    <title>Add Subscriptions</title>
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ padding: 20px; }
    </style>
</head>

<body>
    <script>
        function deleteSubscription(pageId){
            console.log(pageId);
            $.post("/delete-subscription.php", {pageId: pageId});
            window.location.reload()
        }

        function editSubscription(pageId){
            console.log(pageId);
            $.post("edit-subscription.php", {pageId: pageId});
            window.location.href = "edit-subscription.php";
        }
    </script>
<?php include 'header.php' ?>
    <div class="wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>URL</th>
                    <th>Title</th>
                    <th>Options</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($result as $row) { ?>
                <tr>
                    <td> <?php echo $row["url"]; ?></td>
                    <td> <?php echo $row["title"]; ?></td>
                    <td> 
                        <p>

                            <button type="button" class="btn btn-link" 
                                onClick="editSubscription(<?php echo $row["pageId"]; ?>)">
                                Edit</button>

                            <button type="button" class="btn btn-link" 
                                onClick="deleteSubscription(<?php echo $row["pageId"]; ?>)">
                                Delete</button>
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
