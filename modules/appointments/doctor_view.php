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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background: #F0F4F8; margin: 0; padding: 20px;">

<div style="max-width: 900px; margin: 0 auto;">
    <a href="../../doctor_dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    <h2 style="text-align:center;"><i class="fas fa-calendar-check"></i> My Appointments</h2>

    <?php while ($row = $result->fetch_assoc()): ?>
    <div class="data-card">
        <p><strong><i class="fas fa-user" style="color: var(--primary);"></i> Patient:</strong> <?= htmlspecialchars($row['patient_name']) ?></p>
        <p><strong><i class="fas fa-calendar" style="color: var(--primary);"></i> Date:</strong> <?= htmlspecialchars($row['appointment_date']) ?></p>
        <p><strong><i class="fas fa-info-circle" style="color: var(--primary);"></i> Status:</strong>
            <?php
            $status_class = 'badge-pending';
            if ($row['status'] === 'Scheduled') $status_class = 'badge-scheduled';
            elseif ($row['status'] === 'Completed') $status_class = 'badge-completed';
            elseif (strpos($row['status'], 'Cancelled') !== false) $status_class = 'badge-cancelled';
            ?>
            <span class="badge <?= $status_class ?>"><?= htmlspecialchars($row['status']) ?></span>
        </p>
        <div class="card-actions">
            <a class="btn-reschedule" href="../../appointments/reschedule.php?id=<?= $row['appointment_id'] ?>"><i class="fas fa-clock"></i> Reschedule</a>
            <a class="btn-cancel" href="../../appointments/cancel.php?id=<?= $row['appointment_id'] ?>&by=doctor" onclick="return confirm('Cancel this appointment?')"><i class="fas fa-times"></i> Cancel</a>
            <a class="btn-reschedule" href="../../appointments/complete.php?id=<?= $row['appointment_id'] ?>" style="background: var(--accent);"><i class="fas fa-check"></i> Complete</a>
        </div>
    </div>
    <?php endwhile; ?>
</div>

</body>
</html>
