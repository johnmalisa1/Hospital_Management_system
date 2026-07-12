<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/Vaccination.php";
require_once "../../includes/classes/Patient.php";
include "../../templates/header.php";

$id = $_GET['id'];
$vaccination = new Vaccination($db);
$patient = new Patient($db);
$row = $vaccination->getVaccinationById($id);
$patients = $patient->getAllPatients();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $vaccine_name = $_POST['vaccine_name'];
    $date_administered = $_POST['date_administered'];
    $notes = $_POST['notes'];

    $vaccination->updateVaccination($id, $patient_id, $vaccine_name, $date_administered, $notes);
    header("Location: view.php");
    exit();
}
?>

<div class="main-content">
    <h2 class="page-title">✏️ Edit Vaccination</h2>
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
            <input type="date" name="date_administered" value="<?= $row['date_administered'] ?>" required>

            <label>Notes:</label>
            <input type="text" name="notes" value="<?= $row['notes'] ?>">

            <button type="submit">Update</button>
        </form>
    </div>
</div>
