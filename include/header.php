<link rel="stylesheet" href="/Assignment/CSS/include/header.css" type="text/css">
<header>
  <h1>
    <a href="/Assignment/index.php">Library</a>
  </h1>

  <nav class="header-nav">
    <?php
    if (isset($_SESSION['username'])) {
      echo '<a href="/web-dev-project/view/logout.php">Log Out</a>';
    } else {
      echo '<a href="/web-dev-project/view/login.php">Log In</a>';
    }
    ?>
  </nav>
</header>