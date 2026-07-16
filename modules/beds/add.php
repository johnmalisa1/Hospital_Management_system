<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ward_id = $_POST['ward_id'];
    $bed_number = $_POST['bed_number'];
    $is_occupied = $_POST['is_occupied'];
    $patient_id = $_POST['patient_id'] ?: null;

    $stmt = $conn->prepare("INSERT INTO beds (ward_id, bed_number, is_occupied, patient_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isii", $ward_id, $bed_number, $is_occupied, $patient_id);
    $stmt->execute();
    header("Location: view.php");
    exit();
}

include "../../templates/header.php";

$wards = $conn->query("SELECT * FROM wards");
$patients = $conn->query("SELECT * FROM patients");
?>

<div class="main-content">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to beds</a>
    <h2 class="page-title">? Add Bed</h2>
    <div class="form-container">
        <form method="POST">
            <label>Ward:</label>
            <select name="ward_id" required>
                <option value="">-- Select Ward --</option>
                <?php while ($w = $wards->fetch_assoc()): ?>
                    <option value="<?= $w['ward_id'] ?>"><?= $w['ward_name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label>Bed Number:</label>
            <input type="text" name="bed_number" required>

            <label>Is Occupied?</label>
            <select name="is_occupied">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>

            <label>Assign Patient (optional):</label>
            <select name="patient_id">
                <option value="">-- None --</option>
                <?php while ($p = $patients->fetch_assoc()): ?>
                    <option value="<?= $p['patient_id'] ?>"><?= $p['name'] ?></option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Save</button>
        </form>
    </div>