<?php
// Database connection code or configuration file if needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the form
    $username = $_POST["username"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $mobile = $_POST["mobile"];

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

    // validation and security measures here to prevent SQL injection

    

    // Check for password length and match
    if (strlen($password) < 6) {
        echo "Password should be at least six characters long.";
    } elseif ($password !== $confirmPassword) {
        echo "Passwords do not match.";
    } elseif (!is_numeric($mobile) || strlen($mobile) !== 10) {
        echo "Mobile number should be numeric and 10 characters long.";
    } else {
        // Check for unique username
        $checkUsername = "SELECT * FROM register WHERE username = '$username'";
        $result = $conn->query($checkUsername);

        if ($result->num_rows > 0) {
            echo "Username is already taken. Please choose a different one.";
        } else {
            // Insert the user data into the database
            $sql = "INSERT INTO register (username, password, first_name, last_name, mobile) 
                    VALUES ('$username', '$password', '$firstname', '$lastname', '$mobile')";

            if ($conn->query($sql) === TRUE) {
                echo "User registered successfully";
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    $conn->close();
}
?>