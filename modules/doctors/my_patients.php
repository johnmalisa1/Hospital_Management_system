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
$patients = $appointment->getPatientsByDoctor($doctor_id);
?>
<?php include "../../templates/header.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>My Patients</title>
</head>

<body class="sidebar-page">
    <div class="main-overlay">

    <h2 style="text-align:center;"><i class="fas fa-user-friends"></i> My Patients</h2>

    <?php
    $seen = [];
    while ($row = $patients->fetch_assoc()):
        if (in_array($row['patient_id'], $seen)) continue;
        $seen[] = $row['patient_id'];

        $status_class = 'badge-pending';
        if ($row['status'] === 'Scheduled') $status_class = 'badge-scheduled';
        elseif ($row['status'] === 'Completed') $status_class = 'badge-completed';
        elseif (strpos($row['status'], 'Cancelled') !== false) $status_class = 'badge-cancelled';
        elseif ($row['status'] === 'Rescheduled') $status_class = 'badge-scheduled';
    ?>
    <div class="data-card">
        <p><strong><i class="fas fa-user" style="color: var(--primary);"></i> Patient:</strong> <?= htmlspecialchars($row['name']) ?></p>
        <p><strong><i class="fas fa-venus-mars" style="color: var(--primary);"></i> Gender:</strong> <?= htmlspecialchars($row['gender']) ?></p>
        <p><strong><i class="fas fa-phone" style="color: var(--primary);"></i> Phone:</strong> <?= htmlspecialchars($row['phone']) ?></p>
        <p><strong><i class="fas fa-calendar" style="color: var(--primary);"></i> Appointment Date:</strong> <?= htmlspecialchars($row['appointment_date']) ?></p>
        <p><strong><i class="fas fa-info-circle" style="color: var(--primary);"></i> Status:</strong>
            <span class="badge <?= $status_class ?>"><?= htmlspecialchars($row['status']) ?></span>
        </p>
        <div class="card-actions">
            <a class="btn edit-btn" href="patient_profile.php?patient_id=<?= $row['patient_id'] ?>"><i class="fas fa-id-card"></i> View Profile</a>
        </div>
    </div>
    <?php endwhile; ?>

    <?php if (empty($seen)): ?>
    <p class="no-data">No patients found.</p>
    <?php endif; ?>
</div>

    </div>

<?php include "../../templates/footer.php"; ?>
