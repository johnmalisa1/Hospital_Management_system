<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Appointment.php";

$appointment = new Appointment($db);
$result = $appointment->getAllAppointments();
?>

<?php include "../../templates/header.php"; ?>

<div class="appointment-list">
    <a href="../../dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    <h2><i class="fas fa-calendar-check"></i> All Appointments</h2>

    <?php while ($row = $result->fetch_assoc()): ?>
    <div class="data-card">
        <p><strong><i class="fas fa-user" style="color: var(--primary);"></i> Patient:</strong> <?= htmlspecialchars($row['patient_name']) ?></p>
        <p><strong><i class="fas fa-user-md" style="color: var(--primary);"></i> Doctor:</strong> <?= htmlspecialchars($row['doctor_name']) ?></p>
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
    </div>
    <?php endwhile; ?>
</div>

<?php include "../../templates/footer.php"; ?>

