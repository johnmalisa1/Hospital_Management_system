<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/MedicalHistory.php";
require_once "../../includes/classes/Patient.php";
$medicalHistory = new MedicalHistory($db);
$patient = new Patient($db);
$patients = $patient->getAllPatients();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $condition = $_POST['condition'];
    $treatment = $_POST['treatment'];
    $date_recorded = $_POST['date_recorded'];
    $recorded_by = $_SESSION['user_id'];

    if ($medicalHistory->addHistory($patient_id, $condition, $treatment, $date_recorded, $recorded_by)) {
        header("Location: view.php");
        exit();
    } else {
        $error = "Failed to add history.";
    }
}

include "../../templates/header.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Medical History</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="main-content">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Medical History</a>
<h2>? Add Medical History</h2>

    <form method="POST">
        <label>Patient:</label>
        <select name="patient_id" required>
            <option value="">-- Select Patient --</option>
            <?php while ($row = $patients->fetch_assoc()): ?>
                <option value="<?= $row['patient_id'] ?>"><?= $row['name'] ?></option>
            <?php endwhile; ?>
        </select>

        <label>Condition:</label>
        <input type="text" name="condition" required>

        <label>Treatment:</label>
        <textarea name="treatment"></textarea>

        <label>Date Recorded:</label>
        <input type="date" name="date_recorded" required>

        <button type="submit">Save</button>
    </form>
</div>
</body>
</html>