<?php
session_start();
include "../../config/db.php";
$id = intval($_GET['id']);
$row = $conn->query("SELECT * FROM surgeries WHERE surgery_id = $id")->fetch_assoc();
$patients = $conn->query("SELECT * FROM patients");
$doctors = $conn->query("SELECT * FROM users WHERE role='Doctor'");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $surgery_type = $_POST['surgery_type'];
    $surgery_date = $_POST['surgery_date'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("UPDATE surgeries SET patient_id=?, doctor_id=?, surgery_type=?, surgery_date=?, notes=? WHERE surgery_id=?");
    $stmt->bind_param("iisssi", $patient_id, $doctor_id, $surgery_type, $surgery_date, $notes, $id);
    $stmt->execute();
    header("Location: view.php");
    exit();
}

include "../../templates/header.php";
?>

<div class="main-content">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to surgeries</a>
    <h2 class="page-title">?? Edit Surgery</h2>
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

            <label>Doctor:</label>
            <select name="doctor_id">
                <?php while ($d = $doctors->fetch_assoc()): ?>
                    <option value="<?= $d['id'] ?>" <?= $d['id'] == $row['doctor_id'] ? 'selected' : '' ?>>
                        <?= $d['username'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Surgery Type:</label>
            <input type="text" name="surgery_type" value="<?= $row['surgery_type'] ?>" required>

            <label>Surgery Date:</label>
            <input type="date" name="surgery_date" value="<?= $row['surgery_date'] ?>" required>

            <label>Notes:</label>
            <input type="text" name="notes" value="<?= $row['notes'] ?>">

            <button type="submit">Update</button>
        </form>
    </div>