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
    <h1>Welcome to the Library Website <?php echo $_SESSION['Username']; ?>!</h1>
    <a href="logout.php">Logout</a>
    <a href="view_reserved.php">View Reserved Books</a>
    <a href='search.php' class='back-to-search-btn'>Search</a>
</div>

</body>
</html>


