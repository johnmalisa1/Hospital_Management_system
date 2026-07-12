<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

include "../../config/db.php";
require_once "../../includes/classes/Prescription.php";

$prescription = new Prescription($db);

$id = $_GET['id'];
$row = $prescription->getPrescriptionById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $medicine_id = $_POST['medicine_id'];
    $dosage = $_POST['dosage'];
    $instructions = $_POST['instructions'];
    $date = $_POST['date_issued'];

    $prescription->updatePrescription($id, $patient_id, $doctor_id, $medicine_id, $dosage, $instructions, $date);

    header("Location: view.php");
    exit();
}

?>

<h2 style="text-align:center;">Edit Prescription</h2>
<form method="POST" style="width:500px;margin:auto;padding:30px;background:white;border-radius:10px;box-shadow:0 0 10px #ccc;">
    <label>Dosage:</label>
    <input type="text" name="dosage" value="<?= $row['dosage'] ?>" required style="width:100%;padding:10px;"><br><br>

    <label>Instructions:</label>
    <textarea name="instructions" style="width:100%;height:80px;"><?= $row['instructions'] ?></textarea><br><br>

    <label>Date Issued:</label>
    <input type="date" name="date_issued" value="<?= $row['date_issued'] ?>" required style="width:100%;padding:10px;"><br><br>

    <button type="submit" style="background:#28a745;color:white;padding:10px 20px;">Update</button>
</form>
