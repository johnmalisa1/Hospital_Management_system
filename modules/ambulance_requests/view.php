<?php
session_start();
include "../../config/db.php";
include "../../templates/header.php";
?>


    <h2 class="page-title">🚑 Ambulance Requests</h2>
    <div style="text-align: center; margin-bottom: 20px;">
        <a href="add.php" class="quick-btn">+ Add Request</a>
    </div>

    <div class="table-responsive"><table>
        <tr>
            <th>ID</th>
            <th>Patient</th>
            <th>Request Date</th>
            <th>Status</th>
            <th>Notes</th>
            <th>Actions</th>
        </tr>

        <?php
        $sql = "SELECT ar.*, p.name AS patient_name
                FROM ambulance_requests ar
                JOIN patients p ON ar.patient_id = p.patient_id";
        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['request_id'] ?></td>
            <td><?= $row['patient_name'] ?></td>
            <td><?= $row['request_date'] ?></td>
            <td><?= $row['status'] ?></td>
            <td><?= $row['notes'] ?></td>
            <td class="action-buttons">
                <a href="edit.php?id=<?= $row['request_id'] ?>" class="btn edit-btn">Edit</a>
                <a href="delete.php?id=<?= $row['request_id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table></div>
</div>




