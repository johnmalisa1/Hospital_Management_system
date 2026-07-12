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
$appointment->completeAppointment($appointment_id);

header("Location: ../modules/appointments/doctor_view.php");
exit();
