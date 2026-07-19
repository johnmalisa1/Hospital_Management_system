<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../../login_doctor.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Diagnosis.php";
require_once "../../includes/classes/Appointment.php";
require_once "../../includes/classes/Patient.php";

$doctor_id = $_SESSION['user_id'];
$patient_id = isset($_GET['patient_id']) ? intval($_GET['patient_id']) : 0;

if ($patient_id <= 0) { echo "Access Denied."; exit(); }

$appointment = new Appointment($db);
$check = $appointment->getAppointmentsByDoctor($doctor_id);
$authorized = false;
while ($r = $check->fetch_assoc()) {
    if ($r['patient_id'] == $patient_id) { $authorized = true; break; }
}
if (!$authorized) { echo "Access Denied: Patient not assigned to you."; exit(); }

$diagnosisService = new Diagnosis($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $diagnosis = trim($_POST['diagnosis'] ?? '');
    $date = trim($_POST['date'] ?? '');
    if ($diagnosis !== '' && $date !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
        $diagnosisService->addDiagnosis($patient_id, $diagnosis, $date, $doctor_id);
    }
    header("Location: patient_profile.php?patient_id=" . $patient_id);
    exit();
}

$patientService = new \Patient($db);
$patient = $patientService->getPatientById($patient_id);
?>

<?php include "../../templates/header.php"; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Diagnosis</title>
</head>

<body class="sidebar-page">
    <div class="main-overlay">
    <h2 style="text-align:center;"><i class="fas fa-stethoscope"></i> Add Diagnosis</h2>

    <?php if ($patient): ?>
    <p style="text-align:center;color:var(--text-light);margin-bottom:16px;">Patient: <strong><?= htmlspecialchars($patient['name']) ?></strong></p>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST">
            <label>Diagnosis:</label>
            <input type="text" name="diagnosis" required placeholder="Enter diagnosis">

            <label>Date:</label>
            <input type="date" name="date" value="<?= date('Y-m-d') ?>" required>

            <button type="submit">Save Diagnosis</button>
        </form>
    </div>
</div>

    </div>

<?php include "../../templates/footer.php"; ?>
