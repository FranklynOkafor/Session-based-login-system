<?php
session_start();

// Redirect logged-in users away from signup
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

// Database
$conn = new mysqli('localhost', 'root', '', 'loginusers');

// Handle form
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
    $stmt->bind_param('ss', $username, $password);

    if ($stmt->execute()) {
        header("Location: login.php");
        exit();
    } else {
        $error = "❌ Error: " . $stmt->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>

    <style>
        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .card {
            width: 90%;
            max-width: 380px;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .card h2 {
            margin-bottom: 20px;
            font-size: 26px;
            color: #111827;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #d1d5db;
            font-size: 15px;
            outline: none;
            transition: 0.2s;
        }

        input:focus {
            border-color: #2563eb;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #111827;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            transition: 0.2s;
        }

        button:hover {
            background: #1f2937;
        }

        .link {
            display: block;
            margin-top: 15px;
            font-size: 14px;
            text-decoration: none;
            color: #2563eb;
        }

        .error {
            margin-top: 10px;
            color: red;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <div class="card">
        <h2>Create Account</h2>

        <?php if (!empty($error)): ?>
            <div class="error"><?= $error ?></div>
        <?php endif; ?>

        <form action="signup.php" method="POST">
            <input type="text" name="username" placeholder="Enter Username" required>
            <input type="password" name="password" placeholder="Enter Password" required>

            <button type="submit">Sign Up</button>
        </form>

        <a class="link" href="login.php">Already have an account? Login</a>
    </div>

</body>
</html>
