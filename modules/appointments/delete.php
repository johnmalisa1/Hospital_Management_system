<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/Appointment.php";
$id = $_GET['id'];
$appointment = new Appointment($db);
$appointment->deleteAppointment($id);
header("Location: view.php");
exit();
?>
