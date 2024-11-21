<?php
  session_start();
  session_destroy();
  setcookie('user_name', '', time() - 3600);
  header("Location: Signin.php");
?>