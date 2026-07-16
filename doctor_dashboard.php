<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background: #F0F4F8; margin: 0; padding: 0;">

<div class="dash-header">
    <h2><i class="fas fa-hospital" style="margin-right: 8px;"></i> Praise Hospital</h2>
    <div class="header-actions">
        <span style="font-size: 14px; opacity: 0.9;"><i class="fas fa-user-md"></i> Welcome, Dr. <?= htmlspecialchars($_SESSION['username']) ?></span>
        <a class="logout-link-header" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<div class="nav-cards">
    <a href="modules/appointments/doctor_view.php" class="nav-card">
        <i class="fas fa-calendar-check"></i>
        <h3>My Appointments</h3>
        <p>View and manage your appointments</p>
    </a>
    <a href="modules/lab_test_results/doctor_view.php" class="nav-card">
        <i class="fas fa-microscope"></i>
        <h3>Lab Results Issued</h3>
        <p>View lab results you have issued</p>
    </a>
    <a href="modules/prescriptions/doctor_view.php" class="nav-card">
        <i class="fas fa-prescription"></i>
        <h3>Prescriptions Given</h3>
        <p>View your prescriptions</p>
    </a>
    <a href="modules/notifications/doctor_view.php" class="nav-card">
        <i class="fas fa-bell"></i>
        <h3>My Notifications</h3>
        <p>Check your notifications</p>
    </a>
</div>

</body>
</html>
