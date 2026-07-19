<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/Vaccination.php";
require_once "../../includes/classes/Patient.php";
$vaccination = new Vaccination($db);
$patient = new Patient($db);
$id = intval($_GET['id']);
$row = $vaccination->getVaccinationById($id);
$patients = $patient->getAllPatients();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $vaccine_name = $_POST['vaccine_name'];
    $date_administered = $_POST['date_administered'];
    $dose_number = intval($_POST['dose_number']);

    $vaccination->updateVaccination($id, $patient_id, $vaccine_name, $date_administered, $dose_number);
    header("Location: view.php");
    exit();
}

include "../../templates/header.php";
?>

<div class="main-content">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to vaccinations</a>
    <h2 class="page-title">?? Edit Vaccination</h2>
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

            <label>Vaccine Name:</label>
            <input type="text" name="vaccine_name" value="<?= $row['vaccine_name'] ?>" required>

            <label>Date Administered:</label>
            <input type="date" name="date_administered" value="<?= $row['vaccination_date'] ?>" required>

            <label>Dose Number:</label>
            <input type="number" name="dose_number" value="<?= $row['dose_number'] ?>" min="1" required>

            <button type="submit">Update</button>
        </form>
    </div>