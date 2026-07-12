<?php
session_start();
include "../../config/db.php";
include "../../templates/header.php";
?>

<div class="main-content">
    <h2 class="page-title">🛏️ Rooms Log</h2>
    <div class="center-btn">
        <a href="add.php" class="quick-btn">+ Add Room Log</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Room No</th>
            <th>Patient</th>
            <th>Admission Date</th>
            <th>Discharge Date</th>
            <th>Actions</th>
        </tr>

        <?php
        $sql = "SELECT rl.*, p.name AS patient_name
                FROM rooms_log rl
                JOIN patients p ON rl.patient_id = p.patient_id";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['log_id'] ?></td>
            <td><?= $row['room_number'] ?></td>
            <td><?= $row['patient_name'] ?></td>
            <td><?= $row['admission_date'] ?></td>
            <td><?= $row['discharge_date'] ?: '-' ?></td>
            <td class="action-buttons">
                <a href="edit.php?id=<?= $row['log_id'] ?>" class="btn edit-btn">Edit</a>
                <a href="delete.php?id=<?= $row['log_id'] ?>" class="btn delete-btn" onclick="return confirm('Delete this entry?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
