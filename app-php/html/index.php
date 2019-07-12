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

if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// check the webpages table for new contents
$webpages_fetch_query = "SELECT url, title FROM webpages LIMIT 30;";
$stmt = $link->prepare($webpages_fetch_query);
$stmt->execute();

$result = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <title>Welcome</title>
    
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
    
        .wrapper{ padding: 20px; }
        
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
        <div class="column">
        <div class="wrapper">
        <table class="table">
            <thead>
                <tr>
                    <th>Title</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach($result as $row) { ?>
                <tr>
                    <td> 
                        <a href="<?php echo utf8_decode(urldecode($row["url"])); ?>" target="page_display" onclick="setTarget('<?php echo utf8_decode(urldecode($row["url"])); ?>')">
                            <?php echo utf8_decode(urldecode($row["url"])); ?>
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

    </div> 
        </div>

        <div class="column">
            <iframe src="https://thehackernews.com/2019/07/whatsapp-android-malware.html" height="100%" width="100%" name="page_display" id="page_display"></iframe>
        </div>
    </div>
</body>
<script>
	let target = document.getElementById("page_display");
	function setTarget(url){
		console.log("setting iframe", url)
		target.src = url;	
	}

</script>
</html>

