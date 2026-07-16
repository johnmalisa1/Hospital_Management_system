<?php
session_start();
include "config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND role = 'Doctor'");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $res = $stmt->get_result();
    $doctor = $res->fetch_assoc();

    if ($doctor && password_verify($password, $doctor['password'])) {
        $_SESSION['user_id'] = $doctor['id'];
        $_SESSION['username'] = $doctor['username'];
        $_SESSION['role'] = 'Doctor';
        header("Location: doctor_dashboard.php");
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Doctor Login</title>
</head>
<body class="login-page">
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-brand">
                <i class="fas fa-user-md"></i>
                <h2>Praise Hospital</h2>
                <span class="role-badge doctor"><i class="fas fa-stethoscope"></i> Doctor Portal</span>
            </div>
            <form method="POST" style="box-shadow: none; padding: 0; width: 100%;">
                <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
                <input type="text" name="username" placeholder="Doctor Username" required>
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
