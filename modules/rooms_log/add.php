<?php
session_start();
include "../../config/db.php";
include "../../templates/header.php";

$patients = $conn->query("SELECT * FROM patients");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_number = $_POST['room_number'];
    $patient_id = $_POST['patient_id'];
    $admission_date = $_POST['admission_date'];
    $discharge_date = $_POST['discharge_date'] ?: null;

    $stmt = $conn->prepare("INSERT INTO rooms_log (room_number, patient_id, admission_date, discharge_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("siss", $room_number, $patient_id, $admission_date, $discharge_date);
    $stmt->execute();
    header("Location: view.php");
    exit();
}
?>

<div class="main-content">
    <h2 class="page-title">➕ Add Room Log</h2>
    <div class="form-container">
        <form method="POST">
            <label>Room Number:</label>
            <input type="text" name="room_number" required>

            <label>Patient:</label>
            <select name="patient_id" required>
                <option value="">-- Select Patient --</option>
                <?php while ($p = $patients->fetch_assoc()): ?>
                    <option value="<?= $p['patient_id'] ?>"><?= $p['name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label>Admission Date:</label>
            <input type="date" name="admission_date" required>

            <label>Discharge Date (optional):</label>
            <input type="date" name="discharge_date">

            <button type="submit">Save</button>
        </form>
    </div>
</div>
