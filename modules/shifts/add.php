<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_id = $_POST['staff_id'];
    $shift_date = $_POST['shift_date'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $role = $_POST['role'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("INSERT INTO shifts (staff_id, shift_date, start_time, end_time, role, notes) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isssss", $staff_id, $shift_date, $start_time, $end_time, $role, $notes);
    $stmt->execute();

    header("Location: view.php");
    exit();
}

$staff = $conn->query("SELECT * FROM users WHERE role != 'Patient'");
?>

<div class="main-content">
    <h2 class="page-title">➕ Assign Shift</h2>
    <div class="form-container">
        <form method="POST">
            <label>Staff:</label>
            <select name="staff_id" required>
                <option value="">-- Select Staff --</option>
                <?php while ($s = $staff->fetch_assoc()): ?>
                    <option value="<?= $s['id'] ?>"><?= $s['username'] ?> (<?= $s['role'] ?>)</option>
                <?php endwhile; ?>
            </select>

            <label>Date:</label>
            <input type="date" name="shift_date" required>

            <label>Start Time:</label>
            <input type="time" name="start_time" required>

            <label>End Time:</label>
            <input type="time" name="end_time" required>

            <label>Role:</label>
            <input type="text" name="role" required>

            <label>Notes:</label>
            <input type="text" name="notes">

            <button type="submit">Save</button>
        </form>
    </div>
</div>
