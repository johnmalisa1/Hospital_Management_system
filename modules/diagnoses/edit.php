<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/Diagnosis.php";
require_once "../../includes/classes/Patient.php";
require_once "../../includes/classes/User.php";
$diagnosisService = new Diagnosis($db);
$patient = new Patient($db);
$userService = new User($db);
$id = intval($_GET['id']);
$row = $diagnosisService->getDiagnosisById($id);
$patients = $patient->getAllPatients();
$doctors = $userService->getDoctorUserAccounts();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $diagnosis = $_POST['diagnosis'];
    $date = $_POST['date'];
    $doctor_id = $_POST['doctor_id'];

    $diagnosisService->updateDiagnosis($id, $patient_id, $diagnosis, $date, $doctor_id);
    header("Location: view.php");
    exit();
}

include "../../templates/header.php";
?>

<div class="main-content">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to diagnoses</a>
    <h2 class="page-title">?? Edit Diagnosis</h2>
    <div class="form-container">
        <form method="POST">
            <label>Patient:</label>
            <select name="patient_id">
                <?php while ($p = $patients->fetch_assoc()): ?>
                    <option value="<?= $p['patient_id'] ?>" <?= $p['patient_id'] == $row['patient_id'] ? 'selected' : '' ?>>
                        <?= $p['name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Diagnosis:</label>
            <input type="text" name="diagnosis" value="<?= $row['diagnosis'] ?>" required>

            <label>Date:</label>
            <input type="date" name="date" value="<?= $row['date'] ?>" required>

            <label>Doctor:</label>
            <select name="doctor_id">
                <?php while ($d = $doctors->fetch_assoc()): ?>
                    <option value="<?= $d['id'] ?>" <?= $d['id'] == $row['doctor_id'] ? 'selected' : '' ?>>
                        <?= $d['username'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Update</button>
        </form>
    </div>