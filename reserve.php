<?php
session_start();

require_once "connection.php";

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the user is not logged in, redirect them to the login page
if (!isset($_SESSION['Username'])) {
    header("Location: login.php");
    exit();
}

$reservationMessage = ""; // Initialize an empty message

// Check if ISBN is provided in the URL
if (isset($_GET['isbn'])) {
    $isbn = $_GET['isbn'];

    // Check if the book is available for reservation
    $checkAvailabilityQuery = "SELECT Reserved FROM books WHERE ISBN = ?";
    $checkAvailabilityStmt = $conn->prepare($checkAvailabilityQuery);

    if ($checkAvailabilityStmt) {
        $checkAvailabilityStmt->bind_param("s", $isbn);
        $checkAvailabilityStmt->execute();
        $checkAvailabilityResult = $checkAvailabilityStmt->get_result();

        if ($checkAvailabilityResult && $checkAvailabilityResult->num_rows > 0) {
            $book = $checkAvailabilityResult->fetch_assoc();

            if ($book['Reserved'] == 0) {
                // Check if the user has already reserved this book
                $reservationExistsQuery = "SELECT COUNT(*) as count FROM Reservations WHERE ISBN = ? AND Username = ?";
                $reservationExistsStmt = $conn->prepare($reservationExistsQuery);

                if ($reservationExistsStmt) {
                    $reservationExistsStmt->bind_param("ss", $isbn, $_SESSION['Username']);
                    $reservationExistsStmt->execute();
                    $reservationExistsResult = $reservationExistsStmt->get_result();

                    if ($reservationExistsResult) {
                        $count = $reservationExistsResult->fetch_assoc()['count'];

                        if ($count > 0) {
                            $reservationMessage = "You have already reserved this book.";
                        } else {
                            // Book is available, proceed with reservation
                            $reserveQuery = "INSERT INTO Reservations (ISBN, Username, ReservedDate) VALUES (?, ?, CURRENT_DATE)";
                            $reserveStmt = $conn->prepare($reserveQuery);

                            if ($reserveStmt) {
                                $reserveStmt->bind_param("ss", $isbn, $_SESSION['Username']); // user's username

                                if ($reserveStmt->execute()) {
                                    // Reservation successful
                                    $reservationMessage = "The book has been successfully reserved.";
                                } else {
                                    $reservationMessage = "Error in reservation: " . $reserveStmt->error;
                                }

                                $reserveStmt->close();
                            } else {
                                $reservationMessage = "Error in reservation preparation: " . $conn->error;
                            }
                        }

                        $reservationExistsStmt->close();
                    } else {
                        $reservationMessage = "Error checking reservation existence: " . $reservationExistsStmt->error;
                    }
                } else {
                    $reservationMessage = "Error in reservation existence check preparation: " . $conn->error;
                }
            } else {
                $reservationMessage = "This book is already reserved by someone else.";
            }
        } else {
            $reservationMessage = "Book not found.";
        }

        $checkAvailabilityStmt->close();
    } else {
        $reservationMessage = "Error in availability check preparation: " . $conn->error;
    }
} else {
    $reservationMessage = "ISBN not provided.";
}

$conn->close();
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
    <h1>Welcome to the Library, <?php echo $_SESSION['Username']; ?>!</h1>
    <a href="logout.php">Logout</a>
    <a href="view_reserved.php">View Reserved Books</a>
    <a href='search.php' class='back-to-search-btn'>Search</a>
</div>

<div class="reservation-message">
    <?php echo $reservationMessage; ?>
</div>

<!-- Your other content goes here -->

</body>
</html>
