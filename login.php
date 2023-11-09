<!-- <!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form action="login_process.php" method="post">
        <div>
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div>
            <input type="submit" value="Login">
        </div>
    </form>
</body>
</html>-->


<?php 
session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the form
    $username = $_POST["username"];
    $password = $_POST["password"];

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

    // Validate and sanitize user input to prevent SQL injection
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);
    // can apply similar validation and sanitization to the password

    // Query to check if the username exists and authenticate the user
    $checkUserQuery = "SELECT * FROM Users WHERE username = '$username' and password = '$password' ";
    $result = $conn->query($checkUserQuery);

    if ($result->num_rows == 1) {
        // Username exists, fetch the user's data
        $user = $result->fetch_assoc();
        $hashedPassword = $user["password"];

        // Verify the password using password_verify function
        if (password_verify($password, $hashedPassword)) {
            // Password is correct, user is authenticated

            // Set user information in the session for future use
            $_SESSION['username'] = $username;

            // Redirect to the user's dashboard or another page upon successful login
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Incorrect password. Please try again.";
        }
    } else {
        echo "Username not found. Please check your username.";
    }

    $conn->close();
}
?>

