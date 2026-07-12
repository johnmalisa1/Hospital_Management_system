<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";
?>

<div class="main-content">
    <h2 class="page-title">🕒 Staff Attendance</h2>
    <div class="center-btn">
        <a href="add.php" class="quick-btn">+ Record Attendance</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Staff</th>
            <th>Date</th>
            <th>Time In</th>
            <th>Time Out</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php
        $sql = "SELECT a.*, u.username AS staff_name
                FROM attendance a
                LEFT JOIN users u ON a.staff_id = u.id
                ORDER BY attendance_date DESC";

        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['attendance_id'] ?></td>
            <td><?= $row['staff_name'] ?></td>
            <td><?= $row['attendance_date'] ?></td>
            <td><?= $row['time_in'] ?></td>
            <td><?= $row['time_out'] ?></td>
            <td><?= $row['status'] ?></td>
            <td class="action-buttons">
                <a href="edit.php?id=<?= $row['attendance_id'] ?>" class="btn edit-btn">Edit</a>
                <a href="delete.php?id=<?= $row['attendance_id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
