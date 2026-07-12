<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $doctor_id = $_POST['doctor_id'];
    $surgery_type = $_POST['surgery_type'];
    $operation_date = $_POST['operation_date'];
    $operation_time = $_POST['operation_time'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO operation_schedules (patient_id, doctor_id, surgery_type, operation_date, operation_time, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $patient_id, $doctor_id, $surgery_type, $operation_date, $operation_time, $status);
    $stmt->execute();

    header("Location: view.php");
    exit();
}

$patients = $conn->query("SELECT * FROM patients");
$doctors = $conn->query("SELECT * FROM users WHERE role = 'Doctor'");
?>

<div class="main-content">
    <h2 class="page-title">➕ Schedule Surgery</h2>
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

            <label>Operation Date:</label>
            <input type="date" name="operation_date" required>

            <label>Operation Time:</label>
            <input type="time" name="operation_time" required>

            <label>Status:</label>
            <select name="status">
                <option value="Scheduled">Scheduled</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
            </select>

            <button type="submit">Save</button>
        </form>
    </div>
</div>
