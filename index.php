

<?php
session_start();

require_once "connection.php";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['Username'];
    $password = $_POST['Password'];

    $stmt = $conn->prepare("SELECT Password FROM Users WHERE Username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($storedPassword);

    if ($stmt->fetch()) {
        if (password_verify($password, $storedPassword)) {
            $_SESSION["Username"] = $username;
            $stmt->close();

            // Check if reservation.php exists
            $reservationPage = "search.php";
            if (file_exists($reservationPage)) {
                header("Location: " . $reservationPage);
                exit(); // Make sure to exit after the header to prevent further execution
            } else {
                echo "Error: search.php not found!";
            }
        } else {
            echo "Incorrect Password!";
        }
    } else {
        echo "User not found.";
    }

    

    $stmt->close();
}

$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Library</title>
    <div class="header">
    <h1>Welcome to the Library Website <?php echo $_SESSION['Username']; ?>!</h1>
    <a href="register.php">Register</a>
</div>

</head>
<body>
    <h2>Please Login to your Library Account</h2>

    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <label for="Username">Username:</label>
        <input id="Username" name="Username" required type="text" />
        <label for="Password">Password:</label>
        <input id="Password" name="Password" required type="password" />
        <button type="submit">Login</button>
        <button onclick="location.href='register.php'">Register</button>
    </form>

</body>
</html>

<?php
    // Include the footer
    include('footer.php');
?>








