<?php
session_start();

$root = $_SERVER['DOCUMENT_ROOT'] . '/Assignment';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="/Assignment/CSS/index.css">
  <title>Home</title>
</head>

<body>
  <?php
  include $root . '/include/header.php';
  ?>

  <main>
    <h2>Home</h2>
    <?php require_once $root . '/sql/database_connect.php';
    if (isset($_SESSION['username'])) {
      echo '<h3 class="welcome">Welcome, <strong>' . $_SESSION["username"] . '</strong>.</h3>';
    }
    ?>
    <nav class="main-menu">
      <ul>
        <?php if (isset($_SESSION['username'])) {
          echo '<li><a href="Assignment/search.php">Book Search</a></li>';
        } ?>
        <?php if (isset($_SESSION['username'])) {
          echo '<li><a href="Assignment/reservations.php">My Reservations</a></li>';
        } ?>
        <?php if (!isset($_SESSION['username'])) {
          echo '<li><a href="Assignment/register.php">Register an Account</a></li>';
        } ?>
      </ul>
    </nav>
  </main>

  <?php
  include $root . '/include/footer.php';
  ?>
</body>

</html>