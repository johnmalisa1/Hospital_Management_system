<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: ../login_doctor.php");
    exit();
}

include "../config/db.php";
require_once "../includes/classes/Appointment.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../modules/appointments/doctor_view.php");
    exit();
}

if (!isset($_POST['appointment_id']) || !isset($_POST['cancelled_by'])) {
    die("Invalid request.");
}

$appointment_id = intval($_POST['appointment_id']);
$cancelled_by = $_POST['cancelled_by'];

if (!in_array($cancelled_by, ['doctor', 'patient'], true)) {
    die("Invalid cancellation type.");
}

if ($cancelled_by === 'doctor' && $_SESSION['role'] !== 'Doctor') {
    die("Unauthorized.");
}

if ($cancelled_by === 'patient' && $_SESSION['role'] !== 'Patient') {
    die("Unauthorized.");
}

$appointment = new Appointment($db);
$appt = $appointment->getAppointmentById($appointment_id);

if (!$appt) {
    die("Appointment not found.");
}

if ($cancelled_by === 'doctor' && $appt['doctor_id'] != $_SESSION['user_id']) {
    die("Access Denied: This appointment is not assigned to you.");
}

if ($cancelled_by === 'patient' && $appt['patient_id'] != $_SESSION['user_id']) {
    die("Access Denied: This appointment is not yours.");
}

$appointment->cancelAppointment($appointment_id, $cancelled_by);

require_once "../includes/classes/Notification.php";
$notificationService = new Notification($db);
if ($cancelled_by === 'doctor') {
    $notificationService->addNotification($appt['patient_id'], 'Your appointment on ' . $appt['appointment_date'] . ' has been cancelled by the doctor');
} else {
    $notificationService->addNotification($appt['doctor_id'], 'Appointment on ' . $appt['appointment_date'] . ' has been cancelled by the patient');
}

if ($cancelled_by === 'doctor') {
    header("Location: ../modules/appointments/doctor_view.php");
} else {
    header("Location: ../modules/appointments/patient_view.php");
}
exit();
