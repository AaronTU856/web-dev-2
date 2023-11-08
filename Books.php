<?php
// Database connection code or configuration file if needed

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user input from the form
    $title = $_POST["title"];
    $author = $_POST["author"];
    $category = $_POST["category"];

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

    // Construct the SQL query based on user input
    $sql = "SELECT * FROM books WHERE 1=1"; // Start with a true condition

    if (!empty($title)) {
        $sql .= " AND (title LIKE '%$title%')";
    }

    if (!empty($author)) {
        $sql .= " AND (author LIKE '%$author%')";
    }

    if (!empty($category)) {
        $sql .= " AND (category = '$category')";
    }

    // Execute the query and retrieve the results
    $result = $conn->query($sql);

    // Display the search results
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Display the search results here
        }
    } else {
        echo "No results found.";
    }

    $conn->close();
}
?>


