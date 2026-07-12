<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Patient') {
    header("Location: login_patient.php");
    exit();
}

include "config/db.php";
require_once "includes/classes/Appointment.php";
require_once "includes/classes/LabTestResult.php";

$patient_id = $_SESSION['user_id'];

// Fetch appointments
$appointment = new Appointment($db);
$appointments = $appointment->getAppointmentsByPatient($patient_id);

// Fetch lab test results
$labTestResult = new LabTestResult($db);
$lab_results = $labTestResult->getPatientDashboardResults($patient_id);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Patient Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h2, h3 {
            text-align: center;
        }

        .top-bar {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .top-bar a {
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        .book {
            background: green;
        }

        .logout {
            background: red;
        }

        .section-title {
            text-align: center;
            color: #007bff;
            margin-bottom: 10px;
        }

        .appointment, .lab-result {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin: 15px auto;
            width: 85%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .appointment p, .lab-result p {
            margin: 6px 0;
        }

        .buttons {
            margin-top: 10px;
        }

        .buttons a {
            text-decoration: none;
            padding: 8px 15px;
            margin-right: 10px;
            border-radius: 5px;
            font-weight: bold;
        }

        .cancel {
            background: #dc3545;
            color: white;
        }

        .reschedule {
            background: #ffc107;
            color: white;
        }

        .no-data {
            text-align: center;
            color: gray;
            font-style: italic;
        }
    </style>
</head>
<body>

<h2>Welcome, <?= htmlspecialchars($_SESSION['username']) ?> 👋</h2>

<div class="top-bar">
    <a class="book" href="appointments/book.php">Book Appointment</a>
    <a class="logout" href="logout.php">Logout</a>
</div>

<h3 class="section-title">📅 My Appointments</h3>

<?php if ($appointments->num_rows > 0): ?>
    <?php while ($row = $appointments->fetch_assoc()): ?>
        <div class="appointment">
            <p><strong>Doctor:</strong> <?= htmlspecialchars($row['doctor_name']) ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars($row['appointment_date']) ?></p>
            <p><strong>Status:</strong> <?= htmlspecialchars($row['status']) ?></p>
            <?php if ($row['status'] === 'Scheduled'): ?>
            <div class="buttons">
                <a class="reschedule" href="appointments/reschedule.php?id=<?= $row['appointment_id'] ?>">Reschedule</a>
                <a class="cancel" href="appointments/cancel.php?id=<?= $row['appointment_id'] ?>&by=patient" onclick="return confirm('Cancel this appointment?')">Cancel</a>
            </div>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p class="no-data">You have no appointments yet.</p>
<?php endif; ?>

<h3 class="section-title">🧪 Lab Test Results</h3>

<?php if ($lab_results->num_rows > 0): ?>
    <?php while ($row = $lab_results->fetch_assoc()): ?>
        <div class="lab-result">
            <p><strong>Test:</strong> <?= htmlspecialchars($row['test_name']) ?></p>
            <p><strong>Result:</strong> <?= htmlspecialchars($row['result_text']) ?></p>
            <p><strong>Date:</strong> <?= htmlspecialchars($row['result_date']) ?></p>
        </div>
    <?php endwhile; ?>
<?php else: ?>
    <p class="no-data">No lab test results available.</p>
<?php endif; ?>

</body>
</html>
