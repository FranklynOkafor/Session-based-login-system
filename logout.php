<?php
  session_start();

  // Remove remember me cookie
  setcookie('remember_me', '', time() - 3600, '/');

  // Destroy session
  session_unset();
  session_destroy();
  header('Location: login.php');
  exit();
