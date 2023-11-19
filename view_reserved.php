
<?php
session_start();

require_once "connection.php";


// Check if the user is not logged in, redirect them to the login page
if (!isset($_SESSION['Username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library - Search Results</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="header">
    <h1>Welcome to the Library Website, <?php echo $_SESSION['Username']; ?>!</h1>
    <a href="logout.php">Logout</a>
    <a href='search.php' class='back-to-search-btn'>Search</a>
</div>


<?php


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

</body>
</html>

<?php
    // Include the footer
    include('footer.php');
?>

