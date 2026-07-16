<?php
session_start();
include "../../config/db.php";
$id = intval($_GET['id']);
$row = $conn->query("SELECT * FROM nurses WHERE nurse_id = $id")->fetch_assoc();
$wards = $conn->query("SELECT * FROM wards");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $assigned_ward = $_POST['assigned_ward'];
    $shift_time = $_POST['shift_time'];

    $stmt = $conn->prepare("UPDATE nurses SET name=?, phone=?, assigned_ward=?, shift_time=? WHERE nurse_id=?");
    $stmt->bind_param("ssisi", $name, $phone, $assigned_ward, $shift_time, $id);
    $stmt->execute();
    header("Location: view.php");
    exit();
}

include "../../templates/header.php";
?>

<div class="main-content">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to nurses</a>
    <h2 class="page-title">?? Edit Nurse</h2>
    <div class="form-container">
        <form method="POST">
            <label>Name:</label>
            <input type="text" name="name" value="<?= $row['name'] ?>" required>

            <label>Phone:</label>
            <input type="text" name="phone" value="<?= $row['phone'] ?>" required>

            <label>Assigned Ward:</label>
            <select name="assigned_ward" required>
                <?php while ($w = $wards->fetch_assoc()): ?>
                    <option value="<?= $w['ward_id'] ?>" <?= $w['ward_id'] == $row['assigned_ward'] ? 'selected' : '' ?>>
                        <?= $w['name'] ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Shift Time:</label>
            <input type="text" name="shift_time" value="<?= $row['shift_time'] ?>" required>

            <button type="submit">Update</button>
        </form>
    </div>