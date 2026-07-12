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
    <h2 class="page-title">⏰ Shift Schedules</h2>
    <div class="center-btn">
        <a href="add.php" class="quick-btn">+ Assign Shift</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Staff</th>
            <th>Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Role</th>
            <th>Notes</th>
            <th>Actions</th>
        </tr>

        <?php
        $sql = "SELECT s.*, u.username 
                FROM shifts s 
                LEFT JOIN users u ON s.staff_id = u.id 
                ORDER BY shift_date DESC";

        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['shift_id'] ?></td>
            <td><?= $row['username'] ?></td>
            <td><?= $row['shift_date'] ?></td>
            <td><?= $row['start_time'] ?></td>
            <td><?= $row['end_time'] ?></td>
            <td><?= $row['role'] ?></td>
            <td><?= $row['notes'] ?></td>
            <td class="action-buttons">
                <a href="edit.php?id=<?= $row['shift_id'] ?>" class="btn edit-btn">Edit</a>
                <a href="delete.php?id=<?= $row['shift_id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
