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

// Get reserved books for the current user
$username = $_SESSION['Username'];
$sql = "SELECT Reservations.ISBN, BookTitle, Author, ReservedDate FROM Reservations
        JOIN books ON Reservations.ISBN = books.ISBN
        WHERE Reservations.Username = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display reserved books
    echo "<h2>Your Reserved Books:</h2>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>" . $row['BookTitle'] . ' by ' . $row['Author'] . ' (Reserved on ' . $row['ReservedDate'] . ') - <a href="cancel_reservation.php?isbn=' . $row['ISBN'] . '">Cancel Reservation</a></li>';
    }
    echo "</ul>";

    $stmt->close();
} else {
    echo "Error in query preparation: " . $conn->error;
}

$conn->close();
?>
