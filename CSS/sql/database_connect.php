<?php
   // Create a connection to the database
   $servername = "localhost";
   $db_username = "root"; // Corrected variable name
   $db_password = ""; // Corrected variable name
   $db_name = "BookReservationDB"; // Corrected variable name

   $conn = new mysqli($servername, $db_username, $db_password, $db_name);

   // Check the database connection
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }