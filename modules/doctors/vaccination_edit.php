<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../../login_doctor.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Vaccination.php";
require_once "../../includes/classes/Appointment.php";

$doctor_id = $_SESSION['user_id'];
$id = intval($_GET['id']);

$vaccinationService = new Vaccination($db);
$row = $vaccinationService->getVaccinationById($id);

if (!$row) { echo "Vaccination not found."; exit(); }

$appointment = new Appointment($db);
$check = $appointment->getAppointmentsByDoctor($doctor_id);
$authorized = false;
while ($r = $check->fetch_assoc()) {
    if ($r['patient_id'] == $row['patient_id']) { $authorized = true; break; }
}
if (!$authorized) { echo "Access Denied: Patient not assigned to you."; exit(); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = intval($_POST['patient_id']);
    $vaccine_name = trim($_POST['vaccine_name'] ?? '');
    $date_administered = trim($_POST['date_administered'] ?? '');
    $dose_number = intval($_POST['dose_number'] ?? 0);
    if ($vaccine_name !== '' && $date_administered !== '' && preg_match('/^\d{4}-\d{2}-\d{2}$/', $date_administered) && $dose_number > 0) {
        $vaccinationService->updateVaccination($id, $patient_id, $vaccine_name, $date_administered, $dose_number);
    }
    header("Location: vaccination_view.php");
    exit();
}

$patients = $appointment->getPatientsByDoctor($doctor_id);
?>
<?php include "../../templates/header.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Vaccination</title>
</head>

<body class="sidebar-page">
    <div class="main-overlay">

<div style="max-width: 500px; margin: 0 auto;">
    <h2 style="text-align:center;"><i class="fas fa-syringe"></i> Edit Vaccination</h2>

    <div class="form-container">
        <form method="POST">
            <label>Patient:</label>
            <select name="patient_id" required>
                <?php
                $seen = [];
                while ($p = $patients->fetch_assoc()):
                    if (in_array($p['patient_id'], $seen)) continue;
                    $seen[] = $p['patient_id'];
                ?>
                    <option value="<?= $p['patient_id'] ?>" <?= $p['patient_id'] == $row['patient_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Vaccine Name:</label>
            <input type="text" name="vaccine_name" value="<?= htmlspecialchars($row['vaccine_name']) ?>" required>

            <label>Date Administered:</label>
            <input type="date" name="date_administered" value="<?= htmlspecialchars($row['vaccination_date']) ?>" required>

            <label>Dose Number:</label>
            <input type="number" name="dose_number" value="<?= htmlspecialchars($row['dose_number']) ?>" min="1" required>

            <button type="submit">Update Vaccination</button>
        </form>
    </div>
</div>

    </div>

<?php include "../../templates/footer.php"; ?>
