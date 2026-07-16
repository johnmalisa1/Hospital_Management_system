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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body style="background: #F0F4F8; margin: 0; padding: 0;">

<div class="dash-header">
    <h2><i class="fas fa-hospital" style="margin-right: 8px;"></i> Welcome, <?= htmlspecialchars($_SESSION['username']) ?></h2>
    <div class="header-actions">
        <a class="logout-link-header" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
    </div>
</div>

<div class="top-bar" style="padding-top: 24px;">
    <a class="book" href="appointments/book.php"><i class="fas fa-calendar-plus"></i> Book Appointment</a>
    <a class="book" href="modules/prescriptions/patient_view.php" style="background: var(--primary);"><i class="fas fa-prescription"></i> My Prescriptions</a>
    <a class="book" href="modules/lab_test_results/patient_view.php" style="background: var(--primary-dark);"><i class="fas fa-microscope"></i> My Lab Results</a>
</div>

<div class="data-section">
    <h3 class="section-title"><i class="fas fa-calendar-check"></i> My Appointments</h3>

    <?php if ($appointments->num_rows > 0): ?>
        <?php while ($row = $appointments->fetch_assoc()): ?>
            <div class="data-card">
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
                <?php if ($row['status'] === 'Scheduled'): ?>
                <div class="card-actions">
                    <a class="btn-reschedule" href="appointments/reschedule.php?id=<?= $row['appointment_id'] ?>"><i class="fas fa-clock"></i> Reschedule</a>
                    <a class="btn-cancel" href="appointments/cancel.php?id=<?= $row['appointment_id'] ?>&by=patient" onclick="return confirm('Cancel this appointment?')"><i class="fas fa-times"></i> Cancel</a>
                </div>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="no-data"><i class="fas fa-calendar-times"></i> You have no appointments yet.</p>
    <?php endif; ?>

    <h3 class="section-title" style="margin-top: 32px;"><i class="fas fa-flask"></i> Lab Test Results</h3>

    <?php if ($lab_results->num_rows > 0): ?>
        <?php while ($row = $lab_results->fetch_assoc()): ?>
            <div class="data-card lab-card">
                <p><strong><i class="fas fa-vial" style="color: var(--accent);"></i> Test:</strong> <?= htmlspecialchars($row['test_name']) ?></p>
                <p><strong><i class="fas fa-file-alt" style="color: var(--accent);"></i> Result:</strong> <?= htmlspecialchars($row['result_text']) ?></p>
                <p><strong><i class="fas fa-calendar" style="color: var(--accent);"></i> Date:</strong> <?= htmlspecialchars($row['result_date']) ?></p>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="no-data"><i class="fas fa-flask"></i> No lab test results available.</p>
    <?php endif; ?>
</div>

</body>
</html>
