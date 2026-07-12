<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Treatment.php";
require_once "../../includes/classes/Patient.php";

$id = $_GET['id'];
$treatment = new Treatment($db);
$patient = new Patient($db);
$row = $treatment->getTreatmentById($id);

$patients = $patient->getAllPatients();
$doctors = $treatment->getDoctorUserAccounts();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $treatment->updateTreatment($id, $_POST['patient_id'], $_POST['doctor_id'], $_POST['description'], $_POST['date_given']);
    header("Location: view.php");
}
?>

<style>
    body {
        background: #f4f6f9;
        font-family: Arial, sans-serif;
    }
    .form-container {
        width: 550px;
        margin: 50px auto;
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        margin-bottom: 25px;
    }
    label {
        display: block;
        margin-bottom: 6px;
        font-weight: bold;
    }
    input, select, textarea {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }
    textarea {
        resize: vertical;
    }
    button {
        background: #6f42c1;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        width: 100%;
        font-size: 16px;
    }
</style>

<div class="form-container">
    <h2>✏️ Edit Treatment</h2>
    <form method="POST">
        <label>Patient</label>
        <select name="patient_id" required>
            <?php while ($p = $patients->fetch_assoc()): ?>
                <option value="<?= $p['patient_id'] ?>" <?= $p['patient_id'] == $row['patient_id'] ? 'selected' : '' ?>>
                    <?= $p['name'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Doctor</label>
        <select name="doctor_id" required>
            <?php while ($d = $doctors->fetch_assoc()): ?>
                <option value="<?= $d['id'] ?>" <?= $d['id'] == $row['doctor_id'] ? 'selected' : '' ?>>
                    <?= $d['username'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Description</label>
        <textarea name="description" rows="4" required><?= $row['description'] ?></textarea>

        <label>Date Given</label>
        <input type="date" name="date_given" value="<?= $row['date_given'] ?>" required>

        <button type="submit">Update Treatment</button>
    </form>
</div>
