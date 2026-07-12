<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../../login_doctor.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Appointment.php";


$doctor_id = $_SESSION['user_id'];
$appointment = new Appointment($db);
$result = $appointment->getAppointmentsByDoctor($doctor_id);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Appointments</title>
    <style>
        body {
            font-family: Arial;
            background: #f4f4f4;
            padding: 20px;
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .appointment {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin: 15px auto;
            width: 80%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .appointment p {
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
        .reschedule {
            background: #ffc107;
            color: white;
        }
        .cancel {
            background: #dc3545;
            color: white;
        }
        .complete {
            background: #28a745;
            color: white;
        }
    </style>
</head>
<body>

<h2>My Appointments</h2>

<?php while ($row = $result->fetch_assoc()): ?>
<div class="appointment">
    <p><strong>Patient:</strong> <?= $row['patient_name'] ?></p>
    <p><strong>Date:</strong> <?= $row['appointment_date'] ?></p>
    <p><strong>Status:</strong> <?= $row['status'] ?></p>

    <div class="buttons">
        <a class="reschedule" href="../../appointments/reschedule.php?id=<?= $row['appointment_id'] ?>">Reschedule</a>
        <a class="cancel" href="../../appointments/cancel.php?id=<?= $row['appointment_id'] ?>&by=doctor" onclick="return confirm('Cancel this appointment?')">Cancel</a>
        <a class="complete" href="../../appointments/complete.php?id=<?= $row['appointment_id'] ?>">Complete</a>
    </div>
</div>
<?php endwhile; ?>

</body>
</html>
