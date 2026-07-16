<?php
session_start();
include "../../config/db.php";
include "../../templates/header.php";
?>


    <h2 class="page-title">🧑‍⚕️ Nurses</h2>
    <div style="text-align: center; margin-bottom: 20px;">
        <a href="add.php" class="quick-btn">+ Add Nurse</a>
    </div>

    <div class="table-responsive"><table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Phone</th>
            <th>Assigned Ward</th>
            <th>Shift Time</th>
            <th>Actions</th>
        </tr>

        <?php
        $sql = "SELECT n.*, w.ward_name AS ward_name
        FROM nurses n
        LEFT JOIN wards w ON n.assigned_ward = w.ward_id";
        $result = $db->getConnection()->query($sql);
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['nurse_id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['phone'] ?></td>
            <td><?= $row['ward_name'] ?? 'N/A' ?></td>
            <td><?= $row['shift_time'] ?></td>
            <td class="action-buttons">
                <a href="edit.php?id=<?= $row['nurse_id'] ?>" class="btn edit-btn">Edit</a>
                <a href="delete.php?id=<?= $row['nurse_id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table></div>
</div>




