<?php
session_start();

// Redirect already logged in users
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'loginusers');


// ✅ Auto-login using Remember Me cookie
if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
    $token = $_COOKIE['remember_me'];

    $stmt = $conn->prepare('SELECT * FROM users WHERE remember_token=? LIMIT 1');
    $stmt->bind_param('s', $token);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {
        $user = $result->fetch_assoc();

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header('Location: dashboard.php');
        exit();
    }
}


// ✅ Handle login form
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

            // ✅ Remember Me
            if ($remember) {
                $token = bin2hex(random_bytes(32));

                $stmt2 = $conn->prepare("UPDATE users SET remember_token=? WHERE id=?");
                $stmt2->bind_param("si", $token, $user['id']);
                $stmt2->execute();

                setcookie("remember_me", $token, time() + (30 * 24 * 60 * 60), "/", "", false, true);
            }

            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid password!";
        }
    } else {
        $error = "Username not found!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>
        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            background: #f3f4f6;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .card {
            width: 90%;
            max-width: 380px;
            background: #fff;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 6px 25px rgba(0,0,0,0.1);
            text-align: center;
        }

        .card h2 {
            margin-bottom: 20px;
            font-size: 26px;
            color: #111827;
        }

        input[type="text"],
        input[type="password"] {
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

        label {
            display: flex;
            align-items: center;
            font-size: 14px;
            color: #374151;
            margin: 10px 0;
        }

        label input {
            margin-right: 8px;
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
            transition: 0.25s;
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
            color: red;
            margin: 10px 0;
            font-size: 14px;
        }
    </style>

</head>
<body>

<div class="card">
    <h2>Login</h2>

    <?php if (!empty($error)): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <input type="text" name="username" placeholder="Enter Username" required>
        <input type="password" name="password" placeholder="Enter Password" required>

        <label>
            <input type="checkbox" name="remember"> Remember Me
        </label>

        <button type="submit">Login</button>
    </form>

    <a class="link" href="signup.php">Don't have an account? Register</a>
</div>

</body>
</html>
