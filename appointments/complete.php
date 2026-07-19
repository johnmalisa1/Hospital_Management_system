<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../login_doctor.php");
    exit();
}

include "../config/db.php";
require_once "../includes/classes/Appointment.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../modules/appointments/doctor_view.php");
    exit();
}

if (!isset($_POST['appointment_id'])) {
    die("Appointment ID missing.");
}

$appointment_id = intval($_POST['appointment_id']);
$doctor_id = $_SESSION['user_id'];

$appointment = new Appointment($db);
$appt = $appointment->getAppointmentById($appointment_id);

if (!$appt) {
    die("Appointment not found.");
}

if ($appt['doctor_id'] != $doctor_id) {
    die("Access Denied: This appointment is not assigned to you.");
}

$appointment->completeAppointment($appointment_id);

header("Location: ../modules/appointments/doctor_view.php");
exit();
