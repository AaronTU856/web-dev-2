<?php
session_start();
require_once "connection.php";

if (!isset($_SESSION['Username'])) {
    header("Location: login.php");
    exit();
}

$searchTitle = $searchAuthor = $searchCategory = "";
$result = null;

$resultsPerPage = 5;
$totalResults = 0;
$totalPages = 0;
$currentPage = 1;

if ($_SERVER["REQUEST_METHOD"] == "POST" || ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['page']))) {
    // Process the form submission or handle pagination
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $searchTitle = $_POST['title'];
        $searchAuthor = $_POST['author'];
        $searchCategory = $_POST['category'];
    } elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['page'])) {
        $currentPage = (int)$_GET['page'];
    }

    $whereClause = "1";

    if (!empty($searchTitle)) {
        $whereClause .= " AND BookTitle LIKE '%$searchTitle%'";
    }

    if (!empty($searchAuthor)) {
        $whereClause .= " AND Author LIKE '%$searchAuthor%'";
    }

    if (!empty($searchCategory)) {
        $whereClause .= " AND CategoryID = $searchCategory";
    }

    $whereClause .= " AND Reserved = 0";

    $sql = "SELECT ISBN, BookTitle, Author, Edition, Year, CategoryID, Reserved FROM books WHERE $whereClause";

    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Error in query preparation: " . $conn->error);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $totalResults = $result->num_rows;
    $totalPages = ceil($totalResults / $resultsPerPage);
    $currentPage = max(1, min($currentPage, $totalPages)); // Ensure currentPage is within valid range

    $offset = ($currentPage - 1) * $resultsPerPage;
    $sql .= " LIMIT $offset, $resultsPerPage";

    $stmt->close();
}

// Include the header
include('header.php');
?>

<div class="books-container">
    <h2>Search Results:</h2>
    <ul>
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST" || ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['page']))) {
            // Process the form submission or handle pagination
            $result = $conn->query($sql);

            if ($result && $result->num_rows > 0) {
                while ($book = $result->fetch_assoc()) {
                    echo "<li>" . $book['BookTitle'] . ' by ' . $book['Author'];

                    if ($book['Reserved'] == 0) {
                        echo ' - <a href="reserve.php?isbn=' . $book['ISBN'] . '">Reserve</a>';
                    }

                    echo "</li>";
                }
            } else {
                echo "<li>No results found.</li>";
            }

            echo "<div class='pagination'>";
            for ($page = 1; $page <= $totalPages; $page++) {
                // Use http_build_query to construct the query string with parameters
                $queryParams = array(
                    'page' => $page,
                    'title' => $searchTitle,
                    'author' => $searchAuthor,
                    'category' => $searchCategory
                );
                $queryString = http_build_query($queryParams);

                // Include search parameters in pagination links
                echo "<a href='search.php?$queryString'>$page</a>";
            }
            echo "</div>";

            if ($conn->error) {
                echo "Error: " . $conn->error;
            }
        }
        ?>
    </ul>
</div>

<form action="search.php" method="post">
    <label for="title">Title:</label>
    <input type="text" name="title" value="<?php echo $searchTitle; ?>">

    <label for="author">Author:</label>
    <input type="text" name="author" value="<?php echo $searchAuthor; ?>">

    <label for="category">Category:</label>
    <select name="category">
        <?php
        $categoriesQuery = "SELECT CategoryID, CategoryDetails FROM Category";
        $categoriesResult = $conn->query($categoriesQuery);

        while ($row = $categoriesResult->fetch_assoc()) {
            // Set the selected attribute based on the current search category
            $selected = ($row['CategoryID'] == $searchCategory) ? 'selected' : '';
            echo "<option value='" . $row['CategoryID'] . "' $selected>" . $row['CategoryDetails'] . "</option>";
        }
        ?>
    </select>

    <button type="submit">Search</button>
</form>

<?php
// Include the footer
include('footer.php');
?>
