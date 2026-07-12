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
    $attendance_date = $_POST['attendance_date'];
    $time_in = $_POST['time_in'];
    $time_out = $_POST['time_out'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO attendance (staff_id, attendance_date, time_in, time_out, status) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $staff_id, $attendance_date, $time_in, $time_out, $status);
    $stmt->execute();

    header("Location: view.php");
    exit();
}

$staff = $conn->query("SELECT * FROM users WHERE role != 'Patient'");
?>

<div class="main-content">
    <h2 class="page-title">➕ Record Attendance</h2>
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
            <input type="date" name="attendance_date" required>

            <label>Time In:</label>
            <input type="time" name="time_in" required>

            <label>Time Out:</label>
            <input type="time" name="time_out">

            <label>Status:</label>
            <select name="status">
                <option value="Present">Present</option>
                <option value="Absent">Absent</option>
                <option value="Leave">Leave</option>
            </select>

            <button type="submit">Save</button>
        </form>
    </div>
</div>
