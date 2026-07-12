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
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            padding: 0;
            margin: 0;
        }

        .header {
            background-color: #007bff;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .header h2 {
            margin: 0;
        }

        .logout-btn {
            position: absolute;
            right: 20px;
            top: 20px;
            background: red;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            margin: 40px auto;
            gap: 20px;
        }

        .btn-group a {
            background: #007bff;
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 16px;
            box-shadow: 0 0 6px rgba(0,0,0,0.2);
        }

        .btn-group a:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>Welcome, Dr. <?= $_SESSION['username'] ?> 👨‍⚕️</h2>
    <a class="logout-btn" href="logout.php">Logout</a>
</div>

<div class="btn-group">
    <a href="modules/appointments/doctor_view.php">📅 My Appointments</a>
    <a href="modules/lab_test_results/doctor_view.php">🧪 Lab Results Issued</a>
    <a href="modules/prescriptions/doctor_view.php">💊 Prescriptions Given</a>
    <a href="modules/notifications/doctor_view.php">🔔 My Notifications</a>
</div>

</body>
</html>

