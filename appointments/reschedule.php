<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../login_doctor.php");
    exit();
}

include "../config/db.php";
require_once "../includes/classes/Appointment.php";

if (!isset($_GET['id'])) {
    die("Appointment ID missing.");
}

$appointment_id = intval($_GET['id']);
$doctor_id = $_SESSION['user_id'];
$appointment = new Appointment($db);
$appt = $appointment->getAppointmentById($appointment_id);

if (!$appt) {
    die("Appointment not found.");
}

if ($appt['doctor_id'] != $doctor_id) {
    die("Access Denied: This appointment is not assigned to you.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_date = trim($_POST['appointment_date'] ?? '');

    if ($new_date === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $new_date)) {
        $error = "Please enter a valid date (YYYY-MM-DD).";
    } elseif ($appointment->rescheduleAppointment($appointment_id, $new_date)) {
        require_once "../includes/classes/Notification.php";
        $notificationService = new Notification($db);
        $notificationService->addNotification($appt['patient_id'], 'Your appointment has been rescheduled to ' . $new_date);
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="login-page">

<div class="login-wrapper">
    <div class="login-card">
        <div class="login-brand">
            <i class="fas fa-calendar-alt"></i>
            <h2>Reschedule Appointment</h2>
        </div>
        <form method="POST" style="box-shadow: none; padding: 0; width: 100%;">
            <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
            <label>New Date:</label>
            <input type="date" name="appointment_date" required min="<?= date('Y-m-d') ?>">
            <button type="submit"><i class="fas fa-check"></i> Reschedule</button>
        </form>
        <div style="text-align: center;">
            <a href="../modules/appointments/doctor_view.php" class="back-home"><i class="fas fa-arrow-left"></i> Back to Appointments</a>
        </div>
    </div>
</div>

</body>
</html>
