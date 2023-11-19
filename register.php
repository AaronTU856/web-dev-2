<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User Form</title>
    <link rel="stylesheet" href="css/style.css">

    <!-- Other head elements and styles go here -->
</head>
<body>

<?php
// start a session
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BookReservationDB";

// Create connection
$conn = new mysqli($servername,
$username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

//require_once "connection.php";
   
// Check if the form has been submitted and record added successfully
if (isset($_POST['Username']) && isset($_POST['Firstname']) && isset($_POST['Surname']) && isset($_POST['Password']) && isset($_POST['AddressLine']) && isset($_POST['AddressLine2']) && isset($_POST['City']) && isset($_POST['Email']) && isset($_POST['Telephone'])) {
    $n = $_POST['Username'];
    $f = $_POST['Firstname'];
    $s = $_POST['Surname'];
    $p = password_hash($_POST['Password'], PASSWORD_DEFAULT);
    $a = $_POST['AddressLine'];
    $b = $_POST['AddressLine2'];
    $c = $_POST['City'];
    $e = $_POST['Email'];
    $t = $_POST['Telephone'];

    $stmt = $conn->prepare("INSERT INTO Users (Username, Firstname, Surname, Password, AddressLine, AddressLine2, City, Email, Telephone) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssss", $n, $f, $s, $p, $a, $b, $c, $e, $t);

    if ($stmt->execute()) {
        // Display a button to go to the login page
        echo '<div class="form-container">';
        echo '<p>Record added successfully. <a href="login.php">Go to login page</a></p>';
        echo '</div>';
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

// Your remaining HTML content and PHP code
?>



<div class="form-container">
    <h2>Add User</h2>
    <form method="post" class="user-form">
    
        <label for="Username">Username:</label>
        <input type="text" name="Username" required>

        <label for="Firstname">First Name:</label>
        <input type="text" name="Firstname" required>

        <label for="Surname">Surname:</label>
        <input type="text" name="Surname" required>

        <label for="Password">Password:</label>
        <input type="Password" name="Password" required>

        <label for="AddressLine">Address Line:</label>
        <input type="text" name="AddressLine" required>

        <label for="AddressLine2">Address Line:</label>
        <input type="text" name="AddressLine2">

        <label for="City">City:</label>
        <input type="text" name="City" required>

        <label for="Email">Email:</label>
        <input type="text" name="Email" required>

        <label for="Telephone">Phone:</label>
        <input type="number" name="Telephone" required>


        <p><input type="submit" value="Add New"/></p>
    </form>
</div>



<?php
// Fetch user data from the database
$sql = "SELECT * FROM Users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div class='table-container'>";
    echo "<table border='1'>";
    echo "<tr>
    <tr>
        
        <th>Username</th>
        <th>First Name</th>
        <th>Surname</th>
        <th>Password</th>
        <th>AddressLine</th>
        <th>AddressLine2</th>
        <th>City</th>
        <th>Email</th>
        <th>Telephone</th>
    </tr>
         </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Username'] . "</td>";
        echo "<td>" . $row['Firstname'] . "</td>";
        echo "<td>" . $row['Surname'] . "</td>";
        echo "<td>" . $row['Password'] . "</td>";
        echo "<td>" . $row['AddressLine'] . "</td>";
        echo "<td>" . $row['AddressLine2'] . "</td>";
        echo "<td>" . $row['City'] . "</td>";
        echo "<td>" . $row['Email'] . "</td>";
        echo "<td>" . $row['Telephone'] . "</td>";
        
        echo "</tr>";
    }

    echo "</table>";
    echo "</div>";
} else {
    echo "0 results";
}
$conn->close();
?>

</body>
</html>