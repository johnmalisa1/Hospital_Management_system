<?php
session_start();
include "../../config/db.php";
include "../../templates/header.php";

?>

<div class="main-content">
    <h2 class="page-title">🏥 Surgeries</h2>
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
            <th>Notes</th>
            <th>Actions</th>
        </tr>

        <?php
        $sql = "SELECT s.*, p.name AS patient_name, u.username AS doctor_name
                FROM surgeries s
                JOIN patients p ON s.patient_id = p.patient_id
                JOIN users u ON s.doctor_id = u.id
                ORDER BY surgery_date DESC";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['surgery_id'] ?></td>
            <td><?= $row['patient_name'] ?></td>
            <td><?= $row['doctor_name'] ?></td>
            <td><?= $row['surgery_type'] ?></td>
            <td><?= $row['surgery_date'] ?></td>
            <td><?= $row['notes'] ?></td>
            <td class="action-buttons">
                <a href="edit.php?id=<?= $row['surgery_id'] ?>" class="btn edit-btn">Edit</a>
                <a href="delete.php?id=<?= $row['surgery_id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
