<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../../login_doctor.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Prescription.php";
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

$prescriptionService = new Prescription($db);
$medicines = $prescriptionService->getMedicines();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $medicine_id = intval($_POST['medicine_id']);
    $dosage = trim($_POST['dosage']);
    $instructions = trim($_POST['instructions'] ?? '');
    $date_issued = trim($_POST['date_issued']);
    if ($medicine_id > 0 && $dosage !== '' && $date_issued !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_issued)) {
        $prescriptionService->addPrescription($patient_id, $doctor_id, $medicine_id, $dosage, $instructions, $date_issued);
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
    <title>Add Prescription</title>
</head>

<body class="sidebar-page">
    <div class="main-overlay">

<div style="max-width: 500px; margin: 0 auto;">
    <h2 style="text-align:center;"><i class="fas fa-prescription"></i> Add Prescription</h2>

    <?php if ($patient): ?>
    <p style="text-align:center;color:var(--text-light);margin-bottom:16px;">Patient: <strong><?= htmlspecialchars($patient['name']) ?></strong></p>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST">
            <label>Medicine:</label>
            <select name="medicine_id" required>
                <option value="">-- Select Medicine --</option>
                <?php while ($m = $medicines->fetch_assoc()): ?>
                    <option value="<?= $m['medicine_id'] ?>"><?= htmlspecialchars($m['name']) ?></option>
                <?php endwhile; ?>
            </select>

            <label>Dosage:</label>
            <input type="text" name="dosage" required placeholder="e.g., 500mg twice daily">

            <label>Instructions:</label>
            <textarea name="instructions" rows="3" placeholder="Special instructions..."></textarea>

            <label>Date Issued:</label>
            <input type="date" name="date_issued" value="<?= date('Y-m-d') ?>" required>

            <button type="submit">Save Prescription</button>
        </form>
    </div>
</div>

    </div>

<?php include "../../templates/footer.php"; ?>
