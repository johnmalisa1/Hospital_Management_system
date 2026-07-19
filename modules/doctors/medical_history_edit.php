<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../../login_doctor.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/MedicalHistory.php";
require_once "../../includes/classes/Appointment.php";

$doctor_id = $_SESSION['user_id'];
$id = intval($_GET['id']);
$patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;

if ($patient_id <= 0) { echo "Access Denied."; exit(); }

$appointment = new Appointment($db);
$check = $appointment->getAppointmentsByDoctor($doctor_id);
$authorized = false;
while ($r = $check->fetch_assoc()) {
    if ($r['patient_id'] == $patient_id) { $authorized = true; break; }
}
if (!$authorized) { echo "Access Denied: Patient not assigned to you."; exit(); }

$medicalHistoryService = new MedicalHistory($db);
$row = $medicalHistoryService->getHistoryById($id);

if (!$row || $row['patient_id'] != $patient_id) { echo "Access Denied."; exit(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $condition = trim($_POST['condition'] ?? '');
    $treatment = trim($_POST['treatment'] ?? '');
    $date_recorded = trim($_POST['date_recorded'] ?? '');
    if ($condition !== '' && $date_recorded !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_recorded)) {
        $medicalHistoryService->updateHistory($id, $patient_id, $condition, $treatment, $date_recorded);
    }
    header("Location: patient_profile.php?patient_id=" . $patient_id);
    exit();
}
?>

<?php include "../../templates/header.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Medical History</title>
</head>

<body class="sidebar-page">
    <div class="main-overlay">
    <h2 style="text-align:center;"><i class="fas fa-heartbeat"></i> Edit Medical History</h2>

    <div class="form-container">
        <form method="POST">
            <label>Condition:</label>
            <input type="text" name="condition" value="<?= htmlspecialchars($row['condition']) ?>" required>

            <label>Treatment:</label>
            <textarea name="treatment" rows="3"><?= htmlspecialchars($row['treatment']) ?></textarea>

            <label>Date Recorded:</label>
            <input type="date" name="date_recorded" value="<?= htmlspecialchars($row['date_recorded']) ?>" required>

            <button type="submit">Update History</button>
        </form>
    </div>
</div>

    </div>

<?php include "../../templates/footer.php"; ?>
