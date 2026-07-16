<?php
session_start();
include "../../config/db.php";
$id = intval($_GET['id']);
$row = $conn->query("SELECT * FROM rooms_log WHERE log_id = $id")->fetch_assoc();
$patients = $conn->query("SELECT * FROM patients");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_number = $_POST['room_number'];
    $patient_id = $_POST['patient_id'];
    $admission_date = $_POST['admission_date'];
    $discharge_date = $_POST['discharge_date'] ?: null;

    $stmt = $conn->prepare("UPDATE rooms_log SET room_number=?, patient_id=?, admission_date=?, discharge_date=? WHERE log_id=?");
    $stmt->bind_param("sissi", $room_number, $patient_id, $admission_date, $discharge_date, $id);
    $stmt->execute();
    header("Location: view.php");
    exit();
}

include "../../templates/header.php";
?>

<div class="main-content">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to rooms log</a>
    <h2 class="page-title">?? Edit Room Log</h2>
    <div class="form-container">
        <form method="POST">
            <label>Room Number:</label>
            <input type="text" name="room_number" value="<?= $row['room_number'] ?>" required>

            <label>Patient:</label>
            <select name="patient_id">
                <?php while ($p = $patients->fetch_assoc()): ?>
                    <option value="<?= $p['patient_id'] ?>" <?= $p['patient_id'] == $row['patient_id'] ? 'selected' : '' ?>>
                        <?= $p['name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Admission Date:</label>
            <input type="date" name="admission_date" value="<?= $row['admission_date'] ?>" required>

            <label>Discharge Date:</label>
            <input type="date" name="discharge_date" value="<?= $row['discharge_date'] ?>">

            <button type="submit">Update</button>
        </form>
    </div>