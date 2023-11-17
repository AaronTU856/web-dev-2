<?php
    session_start();
   // Create a connection to the database
   $servername = "localhost";
   $db_username = "root"; // Corrected variable name
   $db_password = ""; // Corrected variable name
   $db_name = "BookReservationDB"; // Corrected variable name

   $conn = new mysqli($servername, $db_username, $db_password, $db_name);

   // Check the database connection
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }

   if($_SERVER["REQUEST_METHOD"] == "POST"){
    $username = $_POST['username'];
    $password = $_POST['password'];

    $usernameQuery = "SELECT password FROM Users where username='$username'";
    $result = $conn->query($usernameQuery);
    $data = $result->fetch_assoc();

    if(/*Check if the user even exists*/){
        if($data['password'] == $password){
            echo "Correct Password!";
            // redirect to library
            // Other shit here :)
        }
        else{
            echo "Incorrect Password!";
        }
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../project/css/style.css">
    <title>User CRUD</title>
</head>
<body>
    <h2>Add User CRUD Operations</h2>

    <form action="<?php echo $_SERVER['PHP_SELF'];?>" method="post">
        <label for="username">Username:</label> 
        <input id="username" name="username" required type="text" />
        <label for="password">Password:</label>
        <input id="password" name="password" required type="password" />
        <button type="submit">Login</Button>
        <button onclick="location.href='./register.php'">Register</button>
    </form>


    <ul>
        <li><a href="register.php">Add Users</a></li>
        
    </ul>
</body>
</html>


