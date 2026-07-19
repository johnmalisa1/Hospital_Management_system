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

include "../../templates/header.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Appointments</title>
</head>

<body class="sidebar-page">
    <div class="main-overlay">

    <div class="page-header">
        <h2><i class="fas fa-calendar-check"></i> My Appointments</h2>
    </div>

    <?php if ($result->num_rows > 0): ?>
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
            <form method="POST" action="../../appointments/cancel.php" style="display:inline;" onsubmit="return confirm('Cancel this appointment?')">
                <input type="hidden" name="appointment_id" value="<?= $row['appointment_id'] ?>">
                <input type="hidden" name="cancelled_by" value="doctor">
                <button type="submit" class="btn-cancel"><i class="fas fa-times"></i> Cancel</button>
            </form>
            <form method="POST" action="../../appointments/complete.php" style="display:inline;">
                <input type="hidden" name="appointment_id" value="<?= $row['appointment_id'] ?>">
                <button type="submit" class="btn-reschedule" style="background: var(--accent);"><i class="fas fa-check"></i> Complete</button>
            </form>
        </div>
    </div>
    <?php endwhile; ?>
    <?php else: ?>
    <p class="no-data">No appointments found.</p>
    <?php endif; ?>

    </div>

<?php include "../../templates/footer.php"; ?>
