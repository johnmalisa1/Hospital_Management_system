<?php
session_start();
include "../../config/db.php";
include "../../templates/header.php";

$patients = $conn->query("SELECT * FROM patients");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $request_date = $_POST['request_date'];
    $status = $_POST['status'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("INSERT INTO ambulance_requests (patient_id, request_date, status, notes) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $patient_id, $request_date, $status, $notes);
    $stmt->execute();
    header("Location: view.php");
    exit();
}
?>

<div class="main-content">
    <h2 class="page-title">➕ Add Ambulance Request</h2>
    <div class="form-container">
        <form method="POST">
            <label>Patient:</label>
            <select name="patient_id" required>
                <option value="">-- Select Patient --</option>
                <?php while ($p = $patients->fetch_assoc()): ?>
                    <option value="<?= $p['patient_id'] ?>"><?= $p['name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label>Request Date:</label>
            <input type="date" name="request_date" required>

            <label>Status:</label>
            <select name="status">
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Dispatched">Dispatched</option>
                <option value="Completed">Completed</option>
            </select>

            <label>Notes:</label>
            <input type="text" name="notes">

            <button type="submit">Save</button>
        </form>
    </div>
</div>
