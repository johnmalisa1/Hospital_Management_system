<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";

$id = $_GET['id'];
$row = $conn->query("SELECT * FROM shifts WHERE shift_id = $id")->fetch_assoc();
$staff = $conn->query("SELECT * FROM users WHERE role != 'Patient'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_id = $_POST['staff_id'];
    $shift_date = $_POST['shift_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $role = $_POST['role'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("UPDATE shifts SET staff_id=?, shift_date=?, start_time=?, end_time=?, role=?, notes=? WHERE shift_id=?");
    $stmt->bind_param("isssssi", $staff_id, $shift_date, $start_time, $end_time, $role, $notes, $id);
    $stmt->execute();

    header("Location: view.php");
    exit();
}
?>

<div class="main-content">
    <h2 class="page-title">✏️ Edit Shift</h2>
    <div class="form-container">
        <form method="POST">
            <label>Staff:</label>
            <select name="staff_id">
                <?php while ($s = $staff->fetch_assoc()): ?>
                    <option value="<?= $s['id'] ?>" <?= $row['staff_id'] == $s['id'] ? 'selected' : '' ?>>
                        <?= $s['username'] ?> (<?= $s['role'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Date:</label>
            <input type="date" name="shift_date" value="<?= $row['shift_date'] ?>" required>

            <label>Start Time:</label>
            <input type="time" name="start_time" value="<?= $row['start_time'] ?>" required>

            <label>End Time:</label>
            <input type="time" name="end_time" value="<?= $row['end_time'] ?>" required>

            <label>Role:</label>
            <input type="text" name="role" value="<?= $row['role'] ?>" required>

            <label>Notes:</label>
            <input type="text" name="notes" value="<?= $row['notes'] ?>">

            <button type="submit">Update</button>
        </form>
    </div>
</div>
