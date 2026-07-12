<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Appointment.php";
require_once "../../includes/classes/Patient.php";

$appointmentService = new Appointment($db);
$patient = new Patient($db);

$id = $_GET['id'] ?? 0;
$appointment = $appointmentService->getAppointmentById($id);

if (!$appointment) {
    die("Appointment not found.");
}

// Get all patients and doctors
$patients = $patient->getAllPatients();
$doctors = $appointmentService->getDoctorUserAccounts();

// Handle update form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $date = $_POST['appointment_date'];

    $appointmentService->updateAppointment($id, $patient_id, $doctor_id, $date);

    header("Location: view.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Appointment</title>
    <style>
        body { font-family: Arial; background: #f4f4f4; padding: 40px; text-align: center; }
        form {
            background: white; padding: 30px; border-radius: 10px;
            display: inline-block; box-shadow: 0 0 10px #ccc;
        }
        input, select {
            width: 100%; padding: 10px; margin: 10px 0;
        }
        button {
            padding: 10px 20px; background: #007bff; color: white; border: none;
        }
    </style>
</head>
<body>

<form method="POST">
    <h2>Edit Appointment</h2>

    <label>Patient:</label>
    <select name="patient_id" required>
        <option value="">-- Select Patient --</option>
        <?php while ($p = $patients->fetch_assoc()): ?>
            <option value="<?= $p['patient_id'] ?>" <?= ($p['patient_id'] == $appointment['patient_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($p['name']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Doctor:</label>
    <select name="doctor_id" required>
        <option value="">-- Select Doctor --</option>
        <?php while ($d = $doctors->fetch_assoc()): ?>
            <option value="<?= $d['id'] ?>" <?= ($d['id'] == $appointment['doctor_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($d['username']) ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Appointment Date:</label>
    <input type="date" name="appointment_date" value="<?= $appointment['appointment_date'] ?>" required>

    <button type="submit">Update Appointment</button>
</form>

</body>
</html>
