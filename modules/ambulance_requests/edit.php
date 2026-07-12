<?php
session_start();
include "../../config/db.php";
include "../../templates/header.php";

$id = $_GET['id'];
$row = $conn->query("SELECT * FROM ambulance_requests WHERE request_id = $id")->fetch_assoc();
$patients = $conn->query("SELECT * FROM patients");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $request_date = $_POST['request_date'];
    $status = $_POST['status'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("UPDATE ambulance_requests SET patient_id=?, request_date=?, status=?, notes=? WHERE request_id=?");
    $stmt->bind_param("isssi", $patient_id, $request_date, $status, $notes, $id);
    $stmt->execute();
    header("Location: view.php");
    exit();
}
?>

<div class="main-content">
    <h2 class="page-title">✏️ Edit Ambulance Request</h2>
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

            <label>Request Date:</label>
            <input type="date" name="request_date" value="<?= $row['request_date'] ?>" required>

            <label>Status:</label>
            <select name="status">
                <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                <option value="Approved" <?= $row['status'] == 'Approved' ? 'selected' : '' ?>>Approved</option>
                <option value="Dispatched" <?= $row['status'] == 'Dispatched' ? 'selected' : '' ?>>Dispatched</option>
                <option value="Completed" <?= $row['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
            </select>

            <label>Notes:</label>
            <input type="text" name="notes" value="<?= $row['notes'] ?>">

            <button type="submit">Update</button>
        </form>
    </div>
</div>
