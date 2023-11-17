<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add User Form</title>
    <link rel="stylesheet" href="../project/css/style.css">

    <!-- Other head elements and styles go here -->
</head>
<body>

<?php
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

   

if ( isset($_POST['username']) && isset($_POST['firstname'])
&& isset($_POST['surname']) && isset($_POST['password']) && isset($_POST['addressLine']) && isset($_POST['addressLine2']) && isset($_POST['city']) && isset($_POST['email']) && isset($_POST['telephone']) ) {
$n = $_POST['username'];
$f = $_POST['firstname'];
$s = $_POST['surname'];
$p = $_POST['password'];
$a = $_POST['addressLine'];
$b = $_POST['addressLine2'];
$c = $_POST['city'];
$e = $_POST['email'];
$t = $_POST['telephone'];



$sql = "INSERT INTO Users (username, firstname, surname, password, addressLine, addressLine2, city, email, telephone)
VALUES ('$n', '$f', '$s', '$p', '$a', '$b', '$c', '$e', '$t')";
if ($conn->query($sql) === TRUE) {
echo "New record created successfully";
} else {
echo "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
}
?>


<div class="form-container">
    <h2>Add User</h2>
    <form method="post" class="user-form">
        <form method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" required>

        <label for="firstname">First Name:</label>
        <input type="text" name="firstname" required>

        <label for="surname">Surname:</label>
        <input type="text" name="surname" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <label for="addressLine">Address Line:</label>
        <input type="text" name="addressLine" required>

        <label for="addressLine2">Address Line 2:</label>
        <input type="text" name="addressLine2">

        <label for="city">City:</label>
        <input type="text" name="city" required>

        <label for="email">Email:</label>
        <input type="text" name="email" required>

        <label for="telephone">Phone:</label>
        <input type="number" name="telephone" required>


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
        
        <th>username</th>
        <th>firstname</th>
        <th>surname</th>
        <th>password</th>
        <th>addressLine</th>
        <th>addressLine2</th>
        <th>city</th>
        <th>email</th>
        <th>telephone</th>
    </tr>
         </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . $row['firstname'] . "</td>";
        echo "<td>" . $row['surname'] . "</td>";
        echo "<td>" . $row['password'] . "</td>";
        echo "<td>" . $row['addressLine'] . "</td>";
        echo "<td>" . $row['addressLine2'] . "</td>";
        echo "<td>" . $row['city'] . "</td>";
        echo "<td>" . $row['email'] . "</td>";
        echo "<td>" . $row['telephone'] . "</td>";
        
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "0 results";
}
$conn->close();
?>

</body>
</html>
