<?php
session_start();
if (!isset($_SESSION['username'])) {
    header('Location: /Assignment/index.php');
    exit();
}

$root = $_SERVER['DOCUMENT_ROOT'] . '/Assignment';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/Assignment/CSS/reservations.css">
    <title>My Reservations</title>
</head>

<body>
    <?php
    include $root . '/include/header.php';
    ?>

    <main>
        <h2>My Reservations</h2>
        <table class="reservations">
            <tr>
                <th>ISBN</th>
                <th>Title</th>
                <th>Date Made</th>
                <th class="cancel-column">Cancel</th>
            </tr>
            <?php
            require_once $root . '/sql/database_connect.php';

            // If cancel button was clicked:
            if (isset($_POST['cancel'])) {
                $isbn = $_POST['cancel'];
                $deleteReservationQuery = "DELETE FROM reservations WHERE isbn = '$isbn'";
                if (!$conn->query($deleteReservationQuery)) {
                    echo '<p class="error">Error: Reservation not canceled.<p>';
                }
                $updateBookQuery = "UPDATE books SET reserved = false WHERE isbn = '$isbn'";
                if (!$conn->query($updateBookQuery)) {
                    echo '<p class="error">Error: Book reservation status not updated.<p>';
                }
            }

            // Display Table
            $username = $_SESSION['username'];
            $reservationsQuery = "SELECT books.isbn, books.title, Reservations.reservation_date FROM users INNER JOIN Reservations ON Reservations.username = Users.username INNER JOIN books ON books.isbn = Reservations.isbn WHERE Users.username = '$username'";
            $result = $conn->query($reservationsQuery);
            if (!$result) {
                echo '<p class="error">Error connecting to database' . $conn->error . '.</p>';
            } elseif ($result->num_rows === 0) {
                echo '</table>';
                echo '<p>No Reservations Currently Made</p>';
            } else {
                // Next and Previous Buttons
                $offset = 0;
                if (isset($_POST['offset'])) {
                    $offset = $_POST['offset'];
                }

                $rows = $result->fetch_all();
                $current_index = $offset;

                while ((array_key_exists($current_index, $rows)) && ($current_index < $offset + 5)) {
                    $row = $rows[$current_index];
                    $current_index = $current_index + 1;

                    echo '<tr>';
                    echo '<td>' . $row[0] . '</td>';
                    echo '<td>' . $row[1] . '</td>';
                    echo '<td>' . $row[2] . '</td>';
                    // Cancel Button
                    echo '<td class="cancel-column">';
                    echo '<form method="post">';
                    echo '<input type="hidden" name="cancel" value="' . $row['0'] . '">';
                    echo '<button type="submit">Cancel</button>';
                    echo '</form>';
                    echo '</td>';
                    echo '</tr>';
                }
                echo '</table>';

                if ($result->num_rows > 5) {
                    echo '<form method="post" class="page-buttons">';
                    if (($offset - 5) >= 0) {
                        echo '<button type="submit" name="offset" value="' . ($offset - 5) . '">Previous</button>';
                    }
                    if ($result->num_rows > ($offset + 5)) {
                        echo '<button type="submit" name="offset" value="' . ($offset + 5) . '">Next</button>';
                    }
                    echo '</form>';
                }
            }
            ?>
    </main>

    <?php
    include $root . '/include/footer.php';
    ?>
</body>

</html>