<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

include "../../config/db.php";
require_once "../../includes/classes/Appointment.php";
require_once "../../includes/classes/Patient.php";
include "../../navbar.php";

$appointment = new Appointment($db);
$patient = new Patient($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];

    $appointment->bookAppointment($patient_id, $doctor_id, $appointment_date);

    $_SESSION['message'] = "Appointment added successfully.";
    header("Location: view.php");
    exit();
}

$patients = $patient->getAllPatients();
$doctors = $appointment->getDoctorUserAccounts();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Appointment</title>
    <style>
        body {
            font-family: Arial;
            background: #f2f2f2;
            padding: 40px;
        }
        .form-container {
            width: 450px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }
        h2 {
            text-align: center;
            color: #007bff;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 15px;
        }
        select, input[type="date"], button {
            width: 100%;
            padding: 10px;
            margin-top: 8px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            background: #007bff;
            color: white;
            font-weight: bold;
            margin-top: 20px;
            cursor: pointer;
        }
        button:hover {
            background: #0056b3;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Add New Appointment</h2>
    <form method="POST">
        <label for="patient_id">Select Patient</label>
        <select name="patient_id" required>
            <option value="">-- Select Patient --</option>
            <?php while ($row = $patients->fetch_assoc()): ?>
                <option value="<?= $row['patient_id'] ?>"><?= htmlspecialchars($row['name']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="doctor_id">Select Doctor</label>
        <select name="doctor_id" required>
            <option value="">-- Select Doctor --</option>
            <?php while ($row = $doctors->fetch_assoc()): ?>
                <option value="<?= $row['id'] ?>"><?= htmlspecialchars($row['username']) ?></option>
            <?php endwhile; ?>
        </select>

        <label for="appointment_date">Appointment Date</label>
        <input type="date" name="appointment_date" required>

        <button type="submit">Save Appointment</button>
    </form>

    <a href="view.php">← Back to Appointments</a>
</div>

</body>
</html>
