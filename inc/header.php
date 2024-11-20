<?php
  session_start();
  if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
  } else {
    $username = null;
  }

  if (isset($_SESSION['welcome_message'])) {
    echo "<script type='text/javascript'>
            alert('" . htmlspecialchars($_SESSION['welcome_message']) . "');
          </script>";

    // Clear the welcome message from the session
    unset($_SESSION['welcome_message']);
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Restaurant</title>
  <link rel="stylesheet" href="styles/style.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  
</head>
<body>
  <nav>
    <img src="assets/images.png"style="width:5%; height:50px;" alt="logo-img">
    <ul class="nav-links">
      <li><a href="index.php">Home</a></li>
      <li><a href="rooms.php">Rooms</a></li>
      <li><a href="about.php">About</a></li>
      <li><a href="#footer">Contact</a></li>

      <?php if ($username): ?>
        <li><a href="logout.php">Logout</a></li>
      <?php else: ?>
        <li><a href="Signup.php">Sign-up</a></li>
      <?php endif; ?>
      
      
    </ul>
  </nav>

  <div class="nav-spacer"></div>