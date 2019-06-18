<!DOCTYPE html>
<html>
	<head>
		<style>
			.error {color: #ff0000;}
		</style>
	</head>

	<body>
		<?php

			$nameErr = $emailErr = $genderErr = $websiteErr = "";
			$name = $email = $gender = $website = "";

			if ($_SERVER['REQUEST_METHOD'] == "POST"){
				/* form data was successfully submitted */

				/* check each variable if it is empty */
				if (empty($_POST["name"])){
					$nameErr = "Name is required!";
				} else {
					$name = test_input($_POST["name"]);
					// check if name only contains letters and whitespace
					if (!preg_match("/^[a-zA-Z ]*$/", $name)){
						$nameErr = "Only letters and whitespace allowed!";
					}
				}

				if (empty($_POST["email"])) {
					$emailErr = "Email is required";
				  } else {
					$email = test_input($_POST["email"]);
					// check if e-mail address is well-formed
					if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					  $emailErr = "Invalid email format"; 
					}
				  }
					
				  if (empty($_POST["website"])) {
					$website = "";
				  } else {
					$website = test_input($_POST["website"]);
					// check if URL address syntax is valid (this regular expression also allows dashes in the URL)
					if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
					  $websiteErr = "Invalid URL"; 
					}
				  }
				
				  if (empty($_POST["comment"])) {
					$comment = "";
				  } else {
					$comment = test_input($_POST["comment"]);
				  }
				
				  if (empty($_POST["gender"])) {
					$genderErr = "Gender is required";
				  } else {
					$gender = test_input($_POST["gender"]);
				  }
			}

			// TODO: save the user details in the database
			$servername = 'database';
			$username = 'aggregator';
			$password = 'aggregator';
			$dbname = 'db';
			
			// stores the database connection object
			$conn = null;

			try {

				// create a connection to the database
				$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
				// PDO error mode is set to exception
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				echo "Connected Successfully"."<br>";

			} catch (PDOException $ex){
				echo "Connection failed: ".$ex->getMessage()."<br>";
			}
			

			// check if the 'user' table already exists in the database
			// if not create the table 'user'

			$user_table_query = "show tables like 'user'";
			$user_table_result = $conn->query($user_table_query);
			$user_table_result->setFetchMode(PDO::FETCH_ASSOC);

			$user_table_exists = false;
			while ($result = $user_table_result->fetch()){
				if ($result["Tables_in_php_db"] == "user"){
					$user_table_exists = true;
				}
			}

			if ($user_table_exists == true){	
				// the table exists in the database
				echo "Table 'user' already exists in the database"."<br>";
			} else {
				// the table doesn't exist in the database
				try {
					// create a table in the database with attributes: name, email, website, comment, gender
					$create_user_table_query = "create table user(
						id int(6) unsigned auto_increment primary key,
						name varchar(30) not null, 
						email varchar(30) not null,
						website varchar(50), 
						comment varchar(255), 
						gender varchar(10) not null
						)";
	
					$conn->exec($create_user_table_query);
					echo "Table 'user' created successfully"."<br>";
				} catch (PDOException $ex){
					echo "Table creation failed: ".$ex->getMessage()."<br>";
				}
			}
			
			try {
				// add the data entered by the user into the database
				$add_user_query = $conn->prepare("insert into user(name, email, website, comment, gender) 
				values(:name, :email, :website, :comment, :gender);");
				
				$add_user_query->bindParam(':name', $name);
				$add_user_query->bindParam(':email', $email);
				$add_user_query->bindParam(':website', $website);
				$add_user_query->bindParam(':comment', $comment);
				$add_user_query->bindParam(':gender', $gender);

				$add_user_query->execute();
				echo "New records added to database successfully"."<br>";
			} catch (PDOException $ex){
				echo "Adding data to table failed: ".$ex->getMessage()."<br>";
			}

			function test_input($raw_input){
				$raw_input = trim($raw_input);
				$raw_input = stripslashes($raw_input);
				$raw_input = htmlspecialchars($raw_input);
				return $raw_input;
			}
		?>

		<h2>PHP Form Validation Example</h2>
		<p><span class="error">* required</span></p>

		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
			Name: <input type="text" name="name" value="<?php echo $name; ?>">
			<span class="error">* <?php echo $nameErr; ?></span><br><br>
			E-mail: <input type="text" name="email" value="<?php echo $email;?>">
			<span class="error">* <?php echo $emailErr;?></span>
			<br><br>
			Website: <input type="text" name="website" value="<?php echo $website;?>">
			<span class="error"><?php echo $websiteErr;?></span>
			<br><br>
			Comment: <textarea name="comment" rows="5" cols="40"><?php echo $comment;?></textarea>
			<br><br>
			Gender:
			<input type="radio" name="gender" <?php if (isset($gender) && $gender=="female") echo "checked";?> value="female">Female
			<input type="radio" name="gender" <?php if (isset($gender) && $gender=="male") echo "checked";?> value="male">Male
			<input type="radio" name="gender" <?php if (isset($gender) && $gender=="other") echo "checked";?> value="other">Other  
			<span class="error">* <?php echo $genderErr;?></span>
			<br><br>
			<input type="submit" name="submit" value="Submit">  
		</form>
		
		<?php
			echo "<h2>Your Input:</h2>";
			echo $name;
			echo "<br>";
			echo $email;
			echo "<br>";
			echo $website;
			echo "<br>";
			echo $comment;
			echo "<br>";
			echo $gender;
		?>

	</body
</html>

