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
    <h2 class="page-title">🛠️ Operation Theater Schedule</h2>
    <div class="center-btn">
        <a href="add.php" class="quick-btn">+ Schedule Surgery</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Patient</th>
            <th>Doctor</th>
            <th>Type</th>
            <th>Date</th>
            <th>Time</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php
        $sql = "SELECT o.*, p.name AS patient_name, u.username AS doctor_name
                FROM operation_schedules o
                LEFT JOIN patients p ON o.patient_id = p.patient_id
                LEFT JOIN users u ON o.doctor_id = u.id
                ORDER BY operation_date DESC";

        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['schedule_id'] ?></td>
            <td><?= $row['patient_name'] ?></td>
            <td><?= $row['doctor_name'] ?></td>
            <td><?= $row['surgery_type'] ?></td>
            <td><?= $row['operation_date'] ?></td>
            <td><?= $row['operation_time'] ?></td>
            <td><?= $row['status'] ?></td>
            <td class="action-buttons">
                <a href="edit.php?id=<?= $row['schedule_id'] ?>" class="btn edit-btn">Edit</a>
                <a href="delete.php?id=<?= $row['schedule_id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
