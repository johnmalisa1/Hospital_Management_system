<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";

$id = $_GET['id'];
$row = $conn->query("SELECT * FROM beds WHERE bed_id = $id")->fetch_assoc();

$wards = $conn->query("SELECT * FROM wards");
$patients = $conn->query("SELECT * FROM patients");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ward_id = $_POST['ward_id'];
    $bed_number = $_POST['bed_number'];
    $is_occupied = $_POST['is_occupied'];
    $patient_id = $_POST['patient_id'] ?: null;

    $stmt = $conn->prepare("UPDATE beds SET ward_id=?, bed_number=?, is_occupied=?, patient_id=? WHERE bed_id=?");
    $stmt->bind_param("isiii", $ward_id, $bed_number, $is_occupied, $patient_id, $id);
    $stmt->execute();
    header("Location: view.php");
    exit();
}
?>

<div class="main-content">
    <h2 class="page-title">✏️ Edit Bed</h2>
    <div class="form-container">
        <form method="POST">
            <label>Ward:</label>
            <select name="ward_id">
                <?php while ($w = $wards->fetch_assoc()): ?>
                    <option value="<?= $w['ward_id'] ?>" <?= $w['ward_id'] == $row['ward_id'] ? 'selected' : '' ?>><?= $w['ward_name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label>Bed Number:</label>
            <input type="text" name="bed_number" value="<?= $row['bed_number'] ?>" required>

            <label>Is Occupied?</label>
            <select name="is_occupied">
                <option value="0" <?= $row['is_occupied'] == 0 ? 'selected' : '' ?>>No</option>
                <option value="1" <?= $row['is_occupied'] == 1 ? 'selected' : '' ?>>Yes</option>
            </select>

            <label>Assign Patient (optional):</label>
            <select name="patient_id">
                <option value="">-- None --</option>
                <?php while ($p = $patients->fetch_assoc()): ?>
                    <option value="<?= $p['patient_id'] ?>" <?= $row['patient_id'] == $p['patient_id'] ? 'selected' : '' ?>>
                        <?= $p['name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Update</button>
        </form>
    </div>
</div>
