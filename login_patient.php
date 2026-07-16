<?php
session_start();
include "config/db.php";

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT * FROM patients WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    $patient = $result->fetch_assoc();
if ($patient && password_verify($password, $patient['password'])) {
    $_SESSION['user_id'] = $patient['patient_id'];
    $_SESSION['username'] = $patient['username'];
    $_SESSION['role'] = 'Patient';
    header("Location: patient_dashboard.php");
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
    <title>Patient Login</title>
</head>
<body class="login-page">
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-brand">
                <i class="fas fa-user"></i>
                <h2>Praise Hospital</h2>
                <span class="role-badge patient"><i class="fas fa-heartbeat"></i> Patient Portal</span>
            </div>
            <form method="POST" style="box-shadow: none; padding: 0; width: 100%;">
                <?php if ($error): ?>
                    <p class="error"><?= $error ?></p>
                <?php endif; ?>
                <input type="text" name="username" placeholder="Enter Username" required>
                <input type="password" name="password" placeholder="Enter Password" required>
                <button type="submit"><i class="fas fa-sign-in-alt"></i> Login</button>
            </form>
            <div class="form-link">
                Don't have an account? <a href="register_patient.php">Register here</a>
            </div>
            <div style="text-align: center;">
                <a href="index.php" class="back-home"><i class="fas fa-arrow-left"></i> Back to Home</a>
            </div>
        </div>
    </div>
</body>
</html>
