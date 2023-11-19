<?php
session_start();

// Database connection
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "BookReservationDB";

$conn = new mysqli($servername, $db_username, $db_password, $db_name);

// Check the database connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user is not logged in, redirect them to the login page
if (!isset($_SESSION['Username'])) {
    header("Location: login.php");
    exit();
}

// Check if ISBN is provided in the URL
if (isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];

    // Cancel reservation
    $cancelReservationQuery = "DELETE FROM Reservations WHERE ISBN = ? AND Username = ?";
    $cancelReservationStmt = $conn->prepare($cancelReservationQuery);

    if ($cancelReservationStmt) {
        $cancelReservationStmt->bind_param("ss", $isbn, $_SESSION['Username']);
        $cancelReservationStmt->execute();

        if (!$cancelReservationStmt->error) {
            // Reservation canceled successfully
            header("Location: view_reserved.php");
            exit();
        } else {
            echo "Error in cancellation: " . $cancelReservationStmt->error;
        }

        $cancelReservationStmt->close();
    } else {
        echo "Error in cancellation preparation: " . $conn->error;
    }
} else {
    echo "ISBN not provided.";
}

$conn->close();
?>
