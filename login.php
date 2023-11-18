<?php
// 1. Get the form data
// 2. Query the DB to make sure the
//  2a. Account exists,
//  2b. The password is correct, (Please hash your passwords)
// 3. If it does then redirect to your homepage, (library.php)
// 4. If it does not, then return to login and tell the user what's wrong (i.e. 'Incorrect Password', 'User does not exist', etc.)
// 5. Create session

    // Start of Pt. 2
   // Create a connection to the database
  //  
  
  //require_once "connection.php";

  $servername = "localhost";
  $db_username = "root"; // Corrected variable name
  $db_password = ""; // Corrected variable name
  $db_name = "BookReservationDB"; // Corrected variable name

  $conn = new mysqli($servername, $db_username, $db_password, $db_name);

   // Check the database connection
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }


  if (isset($_POST['username']) && isset($_POST['password'])) {
    
    $username = $_POST['username'];
    $password = $_POST['password'];
  
    $logInQuery = "SELECT username, password FROM users WHERE username = '$username'";
    $result = $conn->query($logInQuery);
    if (!$result) {
      echo '<p class="error">Error connecting to database' . $conn->error . '.</p>';
    } elseif ($result->num_rows === 0) {
      echo '<p class="error">Incorrect username or password.</p>';
      return;
    } else {
      $truePassword = mysqli_fetch_array($result)[1];
      if ($password !== $truePassword) {
        echo '<p class="error">Incorrect username or password.</p>';
        return;
      } else {
        $_SESSION["username"] = $username;
        echo '<p class="success">Logged in successfully!</p>';
        echo '<p class="main-menu-link">Click <a href="/Assignment/index.php">here</a> to go to the main menu</p>';
      }
    }
  }

?>



