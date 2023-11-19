
<?php
session_start();

// Create a connection to the database
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
    header("Location: login.php"); // Change "login.php" to your actual login page
    exit();
}

// Initialize variables
$searchTitle = $searchAuthor = $searchCategory = "";
$result = null;

// Set the number of results per page
$resultsPerPage = 5;

// Initialize pagination variables
$totalResults = 0;
$totalPages = 0;
$currentPage = 1;

// Check if the search form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input
    $searchTitle = $_POST['title'];
    $searchAuthor = $_POST['author'];
    $searchCategory = $_POST['category'];

    // Initialize the WHERE clause
    $whereClause = "1"; // Always true initially

    // Build the WHERE clause based on user input
    if (!empty($searchTitle)) {
        $whereClause .= " AND BookTitle LIKE '%$searchTitle%'";
    }

    if (!empty($searchAuthor)) {
        $whereClause .= " AND Author LIKE '%$searchAuthor%'";
    }

    if (!empty($searchCategory)) {
        $whereClause .= " AND CategoryID = $searchCategory";
    }

    // Include condition for available books
    $whereClause .= " AND Reserved = 0";

    // Construct the SQL query with the dynamic WHERE clause
    $sql = "SELECT ISBN, BookTitle, Author, Edition, Year, CategoryID, Reserved FROM books WHERE $whereClause";

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error in query preparation: " . $conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    // Count the total number of results
    $totalResults = $result->num_rows;

    // Calculate the total number of pages
    $totalPages = ceil($totalResults / $resultsPerPage);

    // Get the current page number from the URL, default to 1
    $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

    // Calculate the offset for the SQL query
    $offset = ($currentPage - 1) * $resultsPerPage;

    // Modify your SQL query to include LIMIT for pagination
    $sql .= " LIMIT $offset, $resultsPerPage";

    // Close the prepared statement
    $stmt->close();
}
?>





<?php
// Start a session
session_start();

// Check if the user is not logged in, redirect them to the login page
if (!isset($_SESSION['Username'])) {
    header("Location: login.php"); // Change "login.php" to your actual login page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library - Available Books</title>
    <link rel="stylesheet" href="style.css"> <!-- Add your CSS file or styles here -->
</head>
<body>

<div class="header">
    <h1>Welcome to the Library, <?php echo $_SESSION['Username']; ?>!</h1>
    <a href="logout.php">Logout</a>
    <a href="view_reserved.php">View Reserved Books</a>
    

</div>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library - Search Results</title>
    <link rel="stylesheet" href="css/style.css"> <!-- Add your CSS file or styles here -->
</head>
<body>

<div class="books-container">
    <h2>Search Results:</h2>
    <ul>
        <?php
        // Check if the search form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Execute the modified query
            $result = $conn->query($sql);

            // Check if there are rows returned
            if ($result && $result->num_rows > 0) {
                // Now you can fetch results
                while ($book = $result->fetch_assoc()) {
                    // Display your book information here
                    echo "<li>" . $book['BookTitle'] . ' by ' . $book['Author'];

                    // Display "Reserve" link if the book is not reserved
                    if ($book['Reserved'] == 0) {
                        echo ' - <a href="reserve.php?isbn=' . $book['ISBN'] . '">Reserve</a>';
                    }

                    echo "</li>";
                }
            } else {
                echo "<li>No results found.</li>";
            }

            // Display pagination links
            echo "<div class='pagination'>";
            for ($page = 1; $page <= $totalPages; $page++) {
                echo "<a href='search.php?page=$page'>$page</a>";
            }
            echo "</div>";

            if ($conn->error) {
                echo "Error: " . $conn->error;
            }
        }
        ?>
    </ul>
</div>

</body>
</html>

<div class="books-container">
    <h2>Available Books:</h2>
    <ul>
        <?php
        // Set the number of results per page
        $resultsPerPage = 5;

        // Get the current page number from the URL, default to 1
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

        // Calculate the offset for the SQL query
        $offset = ($currentPage - 1) * $resultsPerPage;

        // Construct the SQL query to get available books with LIMIT
        $sqlAvailableBooks = "SELECT ISBN, BookTitle, Author, Edition, Year, CategoryID, Reserved FROM books WHERE Reserved = 0";

        // Execute the query to get total results
        $resultTotal = $conn->query($sqlAvailableBooks);

        // Check if there are rows returned
        if ($resultTotal && $resultTotal->num_rows > 0) {
            $totalResults = $resultTotal->num_rows;
            $totalPages = ceil($totalResults / $resultsPerPage);

            // Append LIMIT to the SQL query
            $sqlAvailableBooks .= " LIMIT $offset, $resultsPerPage";

            // Execute the query to get paginated results
            $resultAvailableBooks = $conn->query($sqlAvailableBooks);

            // Check if there are rows returned
            if ($resultAvailableBooks && $resultAvailableBooks->num_rows > 0) {
                // Now you can fetch results
                while ($bookAvailable = $resultAvailableBooks->fetch_assoc()) {
                    // Display your available book information here
                    echo "<li>" . $bookAvailable['BookTitle'] . ' by ' . $bookAvailable['Author'] . "</li>";
                }

                // Display pagination links
                echo "<div class='pagination'>";

                // Display "Back" button if not on the first page
                if ($currentPage > 1) {
                    echo "<a href='search.php?page=" . ($currentPage - 1) . "'>Back</a>";
                }

                // Display "Next" button if there are more pages
                if ($currentPage < $totalPages) {
                    echo "<a href='search.php?page=" . ($currentPage + 1) . "'>Next</a>";
                }

                echo "</div>";
            } else {
                echo "<li>No available books found.</li>";
            }
        }

        if ($conn->error) {
            echo "Error: " . $conn->error;
        }
        ?>
    </ul>
</div>




<form action="search.php" method="post">
    <label for="title">Title:</label>
    <input type="text" name="title">

    <label for="author">Author:</label>
    <input type="text" name="author"> <!-- Use lowercase 'author' here -->
    <label for="category">Category:</label>
    <select name="category">
        <!-- Populate dropdown with categories from the database -->
        <?php
        // Assume $conn is your database connection
        $categoriesQuery = "SELECT CategoryID, CategoryDetails FROM Category";
        $categoriesResult = $conn->query($categoriesQuery);

        while ($row = $categoriesResult->fetch_assoc()) {
            echo "<option value='" . $row['CategoryID'] . "'>" . $row['CategoryDetails'] . "</option>";
        }
        ?>
    </select>

    <button type="submit">Search</button>
</form>

</body>
</html>
   
