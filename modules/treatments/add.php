<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Treatment.php";
require_once "../../includes/classes/Patient.php";
require_once "../../includes/classes/User.php";

$treatment = new Treatment($db);
$patient = new Patient($db);
$userService = new User($db);
$patients = $patient->getAllPatients();
$doctors = $userService->getDoctorUserAccounts();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $treatment->addTreatment($_POST['patient_id'], $_POST['doctor_id'], $_POST['description'], $_POST['date_given']);
    header("Location: view.php");
}
?>

<div class="form-container">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Treatments</a>
    <h2>📝 Add Treatment</h2>
    <form method="POST">
        <label>Patient</label>
        <select name="patient_id" required>
            <option value="">-- Select Patient --</option>
            <?php while ($p = $patients->fetch_assoc()): ?>
                <option value="<?= $p['patient_id'] ?>"><?= $p['name'] ?></option>
            <?php endwhile; ?>
        </select>

        <label>Doctor</label>
        <select name="doctor_id" required>
            <option value="">-- Select Doctor --</option>
            <?php while ($d = $doctors->fetch_assoc()): ?>
                <option value="<?= $d['id'] ?>"><?= $d['username'] ?></option>
            <?php endwhile; ?>
        </select>

        <label>Description</label>
        <textarea name="description" rows="4" placeholder="Enter treatment details here..." required></textarea>

        <label>Date Given</label>
        <input type="date" name="date_given" required>

        <button type="submit">Save Treatment</button>
    </form>
</div>
