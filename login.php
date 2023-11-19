<?php

// Start a session
session_start()

require_once "connection.php";

// 1. Get the form data
// 2. Query the DB to make sure the
//  2a. Account exists,
//  2b. The password is correct, (Please hash your passwords)
// 3. If it does then redirect to your homepage, (library.php)
// 4. If it does not, then return to login and tell the user what's wrong (i.e. 'Incorrect Password', 'User does not exist', etc.)
// 5. Create session

    // Start of Pt. 2
   

if (isset($_POST['Username']) && isset($_POST['Password'])) {
    $Username = $_POST['Username'];
    $Password = $_POST['Password'];

    $logInQuery = $conn->prepare("SELECT * FROM Users WHERE Username = ?");
    $logInQuery->bind_param("s", $Username);
    $logInQuery->execute();
    $result = $logInQuery->get_result();

    if ($result->num_rows > 0) {
        // Fetch the associative array
        $row = $result->fetch_assoc();

        // Access the password using the associative key
        $truePasswordHash = $row['Password'];

        if (password_verify($Password, $truePasswordHash)) {
            // Start the session and set the 'username' session variable
            $_SESSION["Username"] = $Username;
            echo '<p class="success">Logged in successfully!</p>';
            
            // Redirect to search page
            header("Location: search.php");
            exit(); // Make sure to exit after the header to prevent further execution
        } else {
            echo '<p class="error">Incorrect Username or Password.</p>';
        }
    } else {
        // User is not registered, display an error and direct them to the register page
        echo '<p class="error">User not found. Click <a href="register.php">here</a> to register.</p>';
    }

    // Close the prepared statement
    $logInQuery->close();
}

// Close the database connection
$conn->close();
?>









