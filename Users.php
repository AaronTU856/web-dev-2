<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="CSS/style.css">
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


if ( isset($_POST['Username']) 
    && isset($_POST['FirstName']) && isset($_POST['LastName']) && isset($_POST['Password']) && isset($_POST['AddressLine']) && isset($_POST['AddressLine2'])&& isset($_POST['City']) && isset($_POST['Email']) && isset($_POST['Telephone'])) {
    $n = $_POST['Username'];
    $f = $_POST['FirstName'];
    $r = $_POST['LastName'];
    $p = $_POST['Password'];
    $a = $_POST['AddressLine'];
    $d = $_POST['AddressLine2'];
    $c = $_POST['City'];
    $e = $_POST['Email'];
    $t = $_POST['Telephone'];

    $sql = "INSERT INTO Users (Username, FirstName, LastName, Password, AddressLine, AddressLine2, City, Email, Telephone)
    VALUES ('$n', '$f', '$r', '$p','$a','$d','$c','$e','$t')";
    if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
    } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}
?>
<div class="form-container">
    <p>Add A New User</p>
    <form method="post">
        <div class="form-field">
            <input type="text" name="Username" placeholder="User Name">
        </div>
        <div class="form-field">
            <input type="text" name="FirstName" placeholder="First Name">
        </div>
        <div class="form-field">
            <input type="text" name="LastName" placeholder="Last Name">
        </div>
        <div class="form-field">
            <input type="password" name="Password" placeholder="Password">
        </div>
        <div class="form-field">
            <input type="text" id="AddressLine" name="AddressLine" placeholder="Address Line">
        </div>
        <div class="form-field">
            <input type="text" name="AddressLine2" placeholder="Address Line 2">
        </div>
        <div class="form-field">
            <input type="text" name="City" placeholder="City">
        </div>
        <div class="form-field">
            <input type="email" name="Email" placeholder="Email">
        </div>
        <div class="form-field">
            <input type="phone" name="Telephone" placeholder="Telephone">
        </div>
        <div class="form-field">
            <input type="submit" value="Add New">
        </div>
    </form>
</div>
<?php
// Fetch user data from the database
$sql = "SELECT * FROM Users";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<table border='1'>";
    echo "<tr>
            <th>Username</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Password</th>
            <th>Address Line</th>
            <th>Address Line2</th>
            <th>City</th>
            <th>Email</th>
            <th>Telephone</th>
    
         </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Username'] . "</td>";
        echo "<td>" . $row['FirstName'] . "</td>";
        echo "<td>" . $row['LastName'] . "</td>";
        echo "<td>" . $row['Password'] . "</td>";
        echo "<td>" . $row['Address Line'] . "</td>";
        echo "<td>" . $row['Address Line2'] . "</td>";
        echo "<td>" . $row['City'] . "</td>";
        echo "<td>" . $row['Email'] . "</td>";
        echo "<td>" . $row['Telephone'] . "</td>";
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
