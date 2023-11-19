<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "BookReservationDb";

//Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

//Check connection
if($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connection successful";
echo "<br>";

if (!$conn->select_db($dbname)) {
    die("Database selection failed: " . $conn->error);
}
?>