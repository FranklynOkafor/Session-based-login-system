<?php
if (isset($_SESSION['user_id'])) {
  header('Location: dashboard.php');
  exit();
}
?>
<form action="login.php" method="POST">
  <input type="text" name="username" placeholder="Enter Username" required><br>
  <input type="password" name="password" placeholder="Enter Password" required><br>
  <label>
    <input type="checkbox" name="remember"> Remember Me
  </label><br>
  <button type="submit">Login</button>
  <a href="signup.php">Register</a>
</form>
<?php

session_start();
$conn = new mysqli('localhost', 'root', '', 'loginusers');


if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
  $token = $_COOKIE['remember_me'];

  $stmt = $conn->prepare('SELECT * FROM users WHERE remember_token =? LIMIT 1');
  $stmt->bind_param('s', $token);
  $stmt->execute();

  $result = $stmt->get_result();


  if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];

    header('Location: dashboard.php');
    # code...
  }
  # code...
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $remember = isset($_POST['remember']);

  $stmt = $conn->prepare('SELECT * FROM users WHERE username=? LIMIT 1');
  $stmt->bind_param('s', $username);
  $stmt->execute();

  $result = $stmt->get_result();



  if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
      $_SESSION['user_id'] = $user['id'];
      $_SESSION['username'] = $user['username'];

      if ($remember) {
        $token = bin2hex((random_bytes(32)));

        $stmt2 = $conn->prepare("UPDATE users SET remember_token=? WHERE id=?");
        $stmt2->bind_param("si", $token, $user['id']);
        $stmt2->execute();

        setcookie("remember_me", $token, time() + (30 * 24 * 60 * 60), "/", "", false, true);
      }

      header('Location: dashboard.php');
      exit();
    }
    echo 'Invalid password';
  }else {
    echo 'Username not found!';
  }
}
?>