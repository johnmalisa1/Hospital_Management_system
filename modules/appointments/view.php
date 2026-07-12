<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Appointment.php";
include "../../navbar.php";

$appointment = new Appointment($db);
$result = $appointment->getAllAppointments();
?>

<!DOCTYPE html>
<html>
<head>
    <title>All Appointments - Admin</title>
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
            width: 85%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .appointment p {
            margin: 6px 0;
        }
        .status {
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>
<body>

<h2>All Appointments</h2>

<?php while ($row = $result->fetch_assoc()): ?>
<div class="appointment">
    <p><strong>Patient:</strong> <?= $row['patient_name'] ?></p>
    <p><strong>Doctor:</strong> <?= $row['doctor_name'] ?></p>
    <p><strong>Date:</strong> <?= $row['appointment_date'] ?></p>
    <p><strong>Status:</strong> <span class="status"><?= $row['status'] ?></span></p>
</div>
<?php endwhile; ?>

</body>
</html>
