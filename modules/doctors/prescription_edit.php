<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../../login_doctor.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Prescription.php";
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

$prescriptionService = new Prescription($db);
$row = $prescriptionService->getPrescriptionById($id);

if (!$row || $row['patient_id'] != $patient_id) { echo "Access Denied."; exit(); }

$medicines = $prescriptionService->getMedicines();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $medicine_id = intval($_POST['medicine_id']);
    $dosage = trim($_POST['dosage']);
    $instructions = trim($_POST['instructions'] ?? '');
    $date_issued = trim($_POST['date_issued']);
    if ($medicine_id > 0 && $dosage !== '' && $date_issued !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_issued)) {
        $prescriptionService->updatePrescription($id, $patient_id, $doctor_id, $medicine_id, $dosage, $instructions, $date_issued);
    }
    header("Location: patient_profile.php?patient_id=" . $patient_id);
    exit();
}
?>
<?php include "../../templates/header.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Prescription</title>
</head>

<body class="sidebar-page">
    <div class="main-overlay">

<div style="max-width: 500px; margin: 0 auto;">
    <h2 style="text-align:center;"><i class="fas fa-prescription"></i> Edit Prescription</h2>

    <div class="form-container">
        <form method="POST">
            <label>Medicine:</label>
            <select name="medicine_id" required>
                <?php while ($m = $medicines->fetch_assoc()): ?>
                    <option value="<?= $m['medicine_id'] ?>" <?= $m['medicine_id'] == $row['medicine_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($m['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Dosage:</label>
            <input type="text" name="dosage" value="<?= htmlspecialchars($row['dosage']) ?>" required>

            <label>Instructions:</label>
            <textarea name="instructions" rows="3"><?= htmlspecialchars($row['instructions']) ?></textarea>

            <label>Date Issued:</label>
            <input type="date" name="date_issued" value="<?= htmlspecialchars($row['date_issued']) ?>" required>

            <button type="submit">Update Prescription</button>
        </form>
    </div>
</div>

    </div>

<?php include "../../templates/footer.php"; ?>
