<?php
session_start();
include "../../config/db.php";
$patients = $conn->query("SELECT * FROM patients");
$doctors = $conn->query("SELECT * FROM users WHERE role='Doctor'");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $surgery_type = $_POST['surgery_type'];
    $surgery_date = $_POST['surgery_date'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("INSERT INTO surgeries (patient_id, doctor_id, surgery_type, surgery_date, notes) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $patient_id, $doctor_id, $surgery_type, $surgery_date, $notes);
    $stmt->execute();
    header("Location: view.php");
    exit();
}

include "../../templates/header.php";
?>

<div class="main-content">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to surgeries</a>
    <h2 class="page-title">? Schedule Surgery</h2>
    <div class="form-container">
        <form method="POST">
            <label>Patient:</label>
            <select name="patient_id" required>
                <option value="">-- Select Patient --</option>
                <?php while ($p = $patients->fetch_assoc()): ?>
                    <option value="<?= $p['patient_id'] ?>"><?= $p['name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label>Doctor:</label>
            <select name="doctor_id" required>
                <option value="">-- Select Doctor --</option>
                <?php while ($d = $doctors->fetch_assoc()): ?>
                    <option value="<?= $d['id'] ?>"><?= $d['username'] ?></option>
                <?php endwhile; ?>
            </select>

            <label>Surgery Type:</label>
            <input type="text" name="surgery_type" required>

            <label>Surgery Date:</label>
            <input type="date" name="surgery_date" required>

            <label>Notes:</label>
            <input type="text" name="notes">

            <button type="submit">Save</button>
        </form>
    </div>