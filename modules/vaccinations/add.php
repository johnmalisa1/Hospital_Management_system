<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/Vaccination.php";
require_once "../../includes/classes/Patient.php";
include "../../templates/header.php";

$vaccination = new Vaccination($db);
$patient = new Patient($db);
$patients = $patient->getAllPatients();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $vaccine_name = $_POST['vaccine_name'];
    $date_administered = $_POST['date_administered'];
    $notes = $_POST['notes'];

    $vaccination->addVaccination($patient_id, $vaccine_name, $date_administered, $notes);
    header("Location: view.php");
    exit();
}
?>

<div class="main-content">
    <h2 class="page-title">➕ Add Vaccination</h2>
    <div class="form-container">
        <form method="POST">
            <label>Patient:</label>
            <select name="patient_id" required>
                <option value="">-- Select Patient --</option>
                <?php while ($p = $patients->fetch_assoc()): ?>
                    <option value="<?= $p['patient_id'] ?>"><?= $p['name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label>Vaccine Name:</label>
            <input type="text" name="vaccine_name" required>

            <label>Date Administered:</label>
            <input type="date" name="date_administered" required>

            <label>Notes:</label>
            <input type="text" name="notes">

            <button type="submit">Save</button>
        </form>
    </div>
</div>
