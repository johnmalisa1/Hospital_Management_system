<?php
session_start();
include "config/db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name     = $_POST['name'];
    $dob      = $_POST['dob'];
    $gender   = $_POST['gender'];
    $phone    = $_POST['phone'];
    $address  = $_POST['address'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Insert into patients table
    $stmt = $conn->prepare("INSERT INTO patients (name, dob, gender, phone, address, username, password) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $name, $dob, $gender, $phone, $address, $username, $password);
    $stmt->execute();

    $_SESSION['patient_username'] = $username;
    header("Location: patient_dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <title>Patient Registration</title>
</head>
<body class="login-page">

<div class="login-wrapper">
    <div class="login-card" style="width: 450px;">
        <div class="login-brand">
            <i class="fas fa-user-plus"></i>
            <h2>Praise Hospital</h2>
            <span class="role-badge patient"><i class="fas fa-heartbeat"></i> Patient Registration</span>
        </div>
        <form method="POST" style="box-shadow: none; padding: 0; width: 100%;">
            <label>Full Name:</label>
            <input type="text" name="name" placeholder="Enter your full name" required>

            <label>Date of Birth:</label>
            <input type="date" name="dob" required>

            <label>Gender:</label>
            <select name="gender" required>
                <option value="">-- Select Gender --</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>

            <label>Phone:</label>
            <input type="text" name="phone" placeholder="Enter phone number" required>

            <label>Address:</label>
            <input type="text" name="address" placeholder="Enter address" required>

            <label>Username:</label>
            <input type="text" name="username" placeholder="Choose a username" required>

            <label>Password:</label>
            <input type="password" name="password" placeholder="Choose a password" required>

            <button type="submit"><i class="fas fa-user-plus"></i> Register</button>
        </form>
        <div class="form-link">
            Already have an account? <a href="login_patient.php">Login here</a>
        </div>
        <div style="text-align: center;">
            <a href="index.php" class="back-home"><i class="fas fa-arrow-left"></i> Back to Home</a>
        </div>
    </div>
</div>

</body>
</html>
