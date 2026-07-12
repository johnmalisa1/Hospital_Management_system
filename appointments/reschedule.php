<?php
session_start();
include "../config/db.php";
require_once "../includes/classes/Appointment.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../login_doctor.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Appointment ID missing.");
}

$appointment_id = $_GET['id'];
$appointment = new Appointment($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_date = $_POST['appointment_date'];

    if ($appointment->rescheduleAppointment($appointment_id, $new_date)) {
        header("Location: ../modules/appointments/doctor_view.php");
        exit();
    } else {
        $error = "Failed to reschedule.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Reschedule Appointment</title>
    <style>
        body {
            background: #f9f9f9;
            font-family: Arial;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }

        input {
            padding: 10px;
            width: 100%;
            margin: 10px 0;
        }

        button {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
        }

        h2 {
            text-align: center;
        }
    </style>
</head>
<body>

<form method="POST">
    <h2>Reschedule Appointment</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <label>New Date:</label>
    <input type="date" name="appointment_date" required>
    <button type="submit">Reschedule</button>
</form>

</body>
</html>
