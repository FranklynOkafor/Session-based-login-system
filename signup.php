<?php
  if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
    
  }
?>
<form action="signup.php" method="POST">
  <input type="text" name="username" placeholder="Enter Username" required><br>
  <input type="password" name="password" placeholder="Enter Password" required><br>
  <button type="submit">Register</button>
  <a href="login.php">Login</a>
</form>
<?php

session_start();
$conn = new mysqli('localhost', "root", '', 'loginusers');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

  $stmt = $conn->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
  $stmt->bind_param('ss', $username, $password);
  

  if ($stmt->execute()) {
    header("Location: login.php");
    exit();
  } else {
    echo "❌ Error: " . $stmt->error;
  }
}
