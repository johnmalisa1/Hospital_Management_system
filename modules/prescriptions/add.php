<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../../login.php"); exit(); }
include "../../config/db.php";
require_once "../../includes/classes/Prescription.php";
require_once "../../includes/classes/Patient.php";
require_once "../../includes/classes/User.php";

$prescription = new Prescription($db);
$patient = new Patient($db);
$userService = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $medicine_id = $_POST['medicine_id'];
    $dosage = $_POST['dosage'];
    $instructions = $_POST['instructions'];
    $date = $_POST['date_issued'];

    $prescription->addPrescription($patient_id, $doctor_id, $medicine_id, $dosage, $instructions, $date);
    header("Location: view.php");
}

$patients = $patient->getAllPatients();
$medicines = $prescription->getMedicines();
$doctors = $userService->getDoctorUserAccounts();
?>

<a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to prescriptions</a>
<h2 style="text-align:center;">Add Prescription</h2>
<form method="POST" style="width:500px;margin:auto;padding:30px;background:white;border-radius:10px;box-shadow:0 0 10px #ccc;">
    <label>Patient:</label>
    <select name="patient_id" required>
        <?php while ($p = $patients->fetch_assoc()): ?>
            <option value="<?= $p['patient_id'] ?>"><?= $p['name'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Doctor:</label>
    <select name="doctor_id" required>
        <?php while ($d = $doctors->fetch_assoc()): ?>
            <option value="<?= $d['id'] ?>"><?= $d['username'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Medicine:</label>
    <select name="medicine_id" required>
        <?php while ($m = $medicines->fetch_assoc()): ?>
            <option value="<?= $m['medicine_id'] ?>"><?= $m['name'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Dosage:</label>
    <input type="text" name="dosage" required style="width:100%;padding:10px;"><br><br>

    <label>Instructions:</label>
    <textarea name="instructions" style="width:100%;height:70px;"></textarea><br><br>

    <label>Date Issued:</label>
    <input type="date" name="date_issued" required style="width:100%;padding:10px;"><br><br>

    <button type="submit" style="background:#28a745;color:white;padding:10px 20px;">Save</button>
</form>

