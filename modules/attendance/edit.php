<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";

$id = $_GET['id'];
$row = $conn->query("SELECT * FROM attendance WHERE attendance_id = $id")->fetch_assoc();
$staff = $conn->query("SELECT * FROM users WHERE role != 'Patient'");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_id = $_POST['staff_id'];
    $attendance_date = $_POST['attendance_date'];
    $time_in = $_POST['time_in'];
    $time_out = $_POST['time_out'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE attendance SET staff_id=?, attendance_date=?, time_in=?, time_out=?, status=? WHERE attendance_id=?");
    $stmt->bind_param("issssi", $staff_id, $attendance_date, $time_in, $time_out, $status, $id);
    $stmt->execute();

    header("Location: view.php");
    exit();
}
?>

<div class="main-content">
    <h2 class="page-title">✏️ Edit Attendance</h2>
    <div class="form-container">
        <form method="POST">
            <label>Staff:</label>
            <select name="staff_id" required>
                <?php while ($s = $staff->fetch_assoc()): ?>
                    <option value="<?= $s['id'] ?>" <?= $row['staff_id'] == $s['id'] ? 'selected' : '' ?>>
                        <?= $s['username'] ?> (<?= $s['role'] ?>)
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Date:</label>
            <input type="date" name="attendance_date" value="<?= $row['attendance_date'] ?>" required>

            <label>Time In:</label>
            <input type="time" name="time_in" value="<?= $row['time_in'] ?>" required>

            <label>Time Out:</label>
            <input type="time" name="time_out" value="<?= $row['time_out'] ?>">

            <label>Status:</label>
            <select name="status">
                <option value="Present" <?= $row['status'] === 'Present' ? 'selected' : '' ?>>Present</option>
                <option value="Absent" <?= $row['status'] === 'Absent' ? 'selected' : '' ?>>Absent</option>
                <option value="Leave" <?= $row['status'] === 'Leave' ? 'selected' : '' ?>>Leave</option>
            </select>

            <button type="submit">Update</button>
        </form>
    </div>
</div>
