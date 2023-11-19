<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

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
                            echo "You have already reserved this book.";
                            exit();
                        }

                        $reservationExistsStmt->close();
                    } else {
                        echo "Error checking reservation existence: " . $reservationExistsStmt->error;
                    }
                } else {
                    echo "Error in reservation existence check preparation: " . $conn->error;
                }

                // Book is available, proceed with reservation
                $reserveQuery = "INSERT INTO Reservations (ISBN, Username, ReservedDate) VALUES (?, ?, CURRENT_DATE)";

                $reserveStmt = $conn->prepare($reserveQuery);

                if ($reserveStmt) {
                    $reserveStmt->bind_param("ss", $isbn, $_SESSION['Username']); // user's username

                    if ($reserveStmt->execute()) {
                        // Reservation successful
                        header("Location: reserve.php"); // Redirect to the search page
                        exit();
                    } else {
                        echo "Error in reservation: " . $reserveStmt->error;
                    }

                    $reserveStmt->close();
                } else {
                    echo "Error in reservation preparation: " . $conn->error;
                }
            } else {
                // Book is already reserved
                echo "This book is already reserved by someone else.";
            }
        } else {
            echo "Book not found.";
        }

        $checkAvailabilityStmt->close();
    } else {
        echo "Error in availability check preparation: " . $conn->error;
    }
} else {
    echo "ISBN not provided.";
}

$conn->close();
?>
