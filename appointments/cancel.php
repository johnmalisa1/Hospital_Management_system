<?php
session_start();
include "../config/db.php";
require_once "../includes/classes/Appointment.php";

if (!isset($_GET['id']) || !isset($_GET['by'])) {
    die("Invalid request.");
}

$appointment_id = $_GET['id'];
$cancelled_by = $_GET['by']; // 'doctor' or 'patient'

$appointment = new Appointment($db);
$appointment->cancelAppointment($appointment_id, $cancelled_by);

header("Location: ../modules/appointments/" . $cancelled_by . "_view.php");
exit();
