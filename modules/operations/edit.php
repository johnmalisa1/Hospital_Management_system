<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";

$id = $_GET['id'];
$row = $conn->query("SELECT * FROM operation_schedules WHERE schedule_id = $id")->fetch_assoc();

$patients = $conn->query("SELECT * FROM patients");
$doctors = $conn->query("SELECT * FROM users WHERE role = 'Doctor'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $surgery_type = $_POST['surgery_type'];
    $operation_date = $_POST['operation_date'];
    $operation_time = $_POST['operation_time'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE operation_schedules SET patient_id=?, doctor_id=?, surgery_type=?, operation_date=?, operation_time=?, status=? WHERE schedule_id=?");
    $stmt->bind_param("iissssi", $patient_id, $doctor_id, $surgery_type, $operation_date, $operation_time, $status, $id);
    $stmt->execute();

    header("Location: view.php");
    exit();
}
?>

<div class="main-content">
    <h2 class="page-title">✏️ Edit Surgery</h2>
    <div class="form-container">
        <form method="POST">
            <label>Patient:</label>
            <select name="patient_id" required>
                <?php while ($p = $patients->fetch_assoc()): ?>
                    <option value="<?= $p['patient_id'] ?>" <?= $row['patient_id'] == $p['patient_id'] ? 'selected' : '' ?>><?= $p['name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label>Doctor:</label>
            <select name="doctor_id" required>
                <?php while ($d = $doctors->fetch_assoc()): ?>
                    <option value="<?= $d['id'] ?>" <?= $row['doctor_id'] == $d['id'] ? 'selected' : '' ?>><?= $d['username'] ?></option>
                <?php endwhile; ?>
            </select>

            <label>Surgery Type:</label>
            <input type="text" name="surgery_type" value="<?= $row['surgery_type'] ?>" required>

            <label>Operation Date:</label>
            <input type="date" name="operation_date" value="<?= $row['operation_date'] ?>" required>

            <label>Operation Time:</label>
            <input type="time" name="operation_time" value="<?= $row['operation_time'] ?>" required>

            <label>Status:</label>
            <select name="status">
                <option value="Scheduled" <?= $row['status'] === 'Scheduled' ? 'selected' : '' ?>>Scheduled</option>
                <option value="Completed" <?= $row['status'] === 'Completed' ? 'selected' : '' ?>>Completed</option>
                <option value="Cancelled" <?= $row['status'] === 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>

            <button type="submit">Update</button>
        </form>
    </div>
</div>
