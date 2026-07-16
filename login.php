<?php
session_start();
include "config/db.php";
require_once "includes/classes/User.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $user = new User($db);
    $admin = $user->getByUsernameAndRole($username, 'Admin');

    

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['user_id'] = $admin['id'];
        $_SESSION['username'] = $admin['username'];
        $_SESSION['role'] = 'Admin';

        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
</head>
<body class="login-page">
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-brand">
                <i class="fas fa-hospital"></i>
                <h2>Praise Hospital</h2>
                <span class="role-badge admin"><i class="fas fa-user-shield"></i> Admin Portal</span>
            </div>
            <form method="POST" style="box-shadow: none; padding: 0; width: 100%;">
                <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
                <input type="text" name="username" placeholder="Username" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit"><i class="fas fa-sign-in-alt"></i> Login</button>
            </form>
            <div style="text-align: center;">
                <a href="index.php" class="back-home"><i class="fas fa-arrow-left"></i> Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>
