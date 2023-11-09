<select name="category">
    <option value="">Select Category</option>
    <?php
    // Retrieve categories from the database
    $categoryQuery = "SELECT category FROM category";
    $categoryResult = $conn->query($categoryQuery);

    while ($categoryRow = $categoryResult->fetch_assoc()) {
        echo '<option value="' . $categoryRow["category"] . '">' . $categoryRow["category"] . '</option>';
    }
    ?>
</select>

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
  <link rel="stylesheet" href="/Assignment/CSS/search.css">
  <title>Search</title>
</head>

<body>
  <?php
  include $root . '/include/header.php';
  ?>

  <main>
    <h2>Search</h2>
    <div class="search-forms">
      <form method="post" class="search-form">
        <?php
        $value = 'placeholder="Type here"';
        if (isset($_POST['search-term'])) {
          $value = 'value="' . $_POST['search-term'] . '"';
        }
        echo '<input type="text" id="search-term" name="search-term" ' . $value . ' required>'
        ?>
        <button>Search</button>
      </form>
      <form method="post" class="category-form">
        <select id="category-id" name="category-id" placeholder="Category" required>
          <option value="" disabled selected hidden>Select a Category</option>
          <?php
          require_once $root . '/model/database_connect.php';
          $categorySelectQuery = 'SELECT id, title FROM categories';
          $result = $conn->query($categorySelectQuery);
          while ($row = $result->fetch_assoc()) {
            echo '<option value="' . $row['id'] . '">' . $row['title'] . '</option>';
          }
          ?>
        </select>
        <button>Search</button>
      </form>
    </div>
    <?php
    require_once $root . '/sql/database_connect.php';

    // If reserve button was clicked
    if (isset($_POST['reserve'])) {
      $isbn = $_POST['reserve'];
      $username = $_SESSION['username'];
      $insertReservationQuery = "INSERT INTO reservations VALUES ('$isbn', '$username', CURRENT_DATE())";
      $conn->query($insertReservationQuery);
      $updateBookQuery = "UPDATE books SET reserved = true WHERE isbn = '$isbn'";
      $conn->query($updateBookQuery);
    }

    // If a search was initiated
    $result = 0;
    if (isset($_POST['search-term'])) {
      $search_term = htmlentities($_POST['search-term']);
      $titleSearch = "SELECT * FROM books WHERE title LIKE '%$search_term%'";
      $authorSearch = "SELECT * FROM books WHERE author LIKE '%$search_term%'";
      $searchQuery = $titleSearch . ' UNION ' . $authorSearch;
      $result = $conn->query($searchQuery);
      if (!$result) {
        echo '<p class="error">Error connecting to database' . $conn->error . '.</p>';
      }
    }
    if (isset($_POST['category-id'])) {
      $category_id = $_POST['category-id'];
      $categoryQuery = "SELECT * FROM books WHERE category_id = '$category_id'";
      $result = $conn->query($categoryQuery);
      if (!$result) {
        echo '<p class="error">Error connecting to database.</p>';
      }
    }

    if (!isset($result->num_rows)) {
      return;
    } elseif ($result->num_rows === 0) {
      echo '<p>No Results</p>';
    } elseif ($result->num_rows > 0) {
      // Table Titles
      echo '<table class="results">';
      echo '<tr>';
      echo '<th>ISBN</th>';
      echo '<th>Title</th>';
      echo '<th>Author</th>';
      echo '<th>Edition</th>';
      echo '<th>Year</th>';
      echo '<th>Category</th>';
      echo '<th>Reserved</th>';
      echo '<th>Reserve</th>';
      echo '</tr>';

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
        echo '<td>' . $row[3] . '</td>';
        echo '<td>' . $row[4] . '</td>';
        echo '<td>' . $row[5] . '</td>';
        $reserve_disabled = '';
        if ($row[6]) {
          echo '<td>Yes</td>';
          $reserve_disabled = ' disabled';
        } else {
          echo '<td>No</td>';
        }
        // Reserve Button
        echo '<td>';
        echo '<form method="post">';
        if (isset($_POST['search-term'])) {
          echo '<input type="hidden" name="search-term" value="' . $_POST['search-term'] . '">';
        } elseif (isset($_POST['category-id'])) {
          echo '<input type="hidden" name="category-id" value="' . $_POST['category-id'] . '"' . $reserve_disabled . '>';
        }
        echo '<input type="hidden" name="offset" value="' . $offset . '">';
        echo '<input type="hidden" name="reserve" value="' . $row['0'] . '">';
        echo '<button type="submit"' . $reserve_disabled . '>Reserve</button>';
        echo '</form>';
        echo '</td>';
        echo '</tr>';
      }
      echo '</table>';

      if ($result->num_rows > 5) {
        echo '<form method="post" class="page-buttons">';
        if (isset($_POST['search-term'])) {
          echo '<input type="hidden" name="search-term" value="' . $_POST['search-term'] . '"/>';
        } elseif (isset($_POST['category-id'])) {
          echo '<input type="hidden" name="category-id" value="' . $_POST['category-id'] . '"/>';
        }
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




