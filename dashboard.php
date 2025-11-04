<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <style>
        body {
            margin: 0;
            font-family: "Poppins", sans-serif;
            background: #f3f4f6;
        }

        .navbar {
            background: #111827;
            padding: 15px 25px;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 600;
        }

        .navbar a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 15px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
            transition: 0.2s;
        }

        .navbar a:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .container {
            width: 90%;
            max-width: 700px;
            margin: 50px auto;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);
            text-align: center;
        }

        .container h2 {
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 10px;
            color: #111827;
        }

        .container p {
            font-size: 16px;
            color: #4b5563;
        }

        .btn {
            margin-top: 20px;
            display: inline-block;
            padding: 12px 22px;
            background: #111827;
            color: white;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: 0.25s;
        }

        .btn:hover {
            background: #1f2937;
        }
    </style>
</head>
<body>

    <div class="navbar">
        <h1>Dashboard</h1>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <h2>Welcome, <?= htmlspecialchars($_SESSION['username']); ?> 👋</h2>
        <p>You are logged in successfully to your practice project.</p>

        <a href="#" class="btn">View Profile</a>
        <a href="#" class="btn" style="background:#2563eb;">Settings</a>
    </div>

</body>
</html>
