<?php
session_start();
include "../../config/db.php";
include "../../templates/header.php";

$wards = $conn->query("SELECT * FROM wards");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $assigned_ward = $_POST['assigned_ward'];
    $shift_time = $_POST['shift_time'];

    $stmt = $conn->prepare("INSERT INTO nurses (name, phone, assigned_ward, shift_time) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssis", $name, $phone, $assigned_ward, $shift_time);
    $stmt->execute();
    header("Location: view.php");
    exit();
}
?>

<div class="main-content">
    <h2 class="page-title">➕ Add Nurse</h2>
    <div class="form-container">
        <form method="POST">
            <label>Name:</label>
            <input type="text" name="name" required>

            <label>Phone:</label>
            <input type="text" name="phone" required>

            <label>Assigned Ward:</label>
            <select name="assigned_ward" required>
                <option value="">-- Select Ward --</option>
                <?php while ($w = $wards->fetch_assoc()): ?>
                    <option value="<?= $w['ward_id'] ?>"><?= $w['name'] ?></option>
                <?php endwhile; ?>
            </select>

            <label>Shift Time:</label>
            <input type="text" name="shift_time" placeholder="e.g. 08:00 - 16:00" required>

            <button type="submit">Save</button>
        </form>
    </div>
</div>
