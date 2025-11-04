<?php
  session_start();

  // Remove remember me cookie
  setcookie('remember me', '', time() - 3600, '/');

  // Destroy session
  session_unset();
  session_destroy();
  header('Location: login.php');
  exit();
