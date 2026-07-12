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
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Patient Registration</title>
</head>
<body class="login-page">

<div class="login-wrapper">
    <form method="POST">
        <h2>Register as Patient</h2>
        <label>Full Name:</label>
        <input type="text" name="name" required>

        <label>Date of Birth:</label>
        <input type="date" name="dob" required>

        <label>Gender:</label>
        <select name="gender" required>
            <option value="">-- Select Gender --</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>

        <label>Phone:</label>
        <input type="text" name="phone" required>

        <label>Address:</label>
        <input type="text" name="address" required>

        <label>Username:</label>
        <input type="text" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <button type="submit">Register</button>
    </form>
</div>

</body>

</html>
