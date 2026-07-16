<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";
?>


    <h2 class="page-title">🛏️ Bed Management</h2>
    <div style="text-align: center; margin-bottom: 20px;">
        <a href="add.php" class="quick-btn">+ Add Bed</a>
    </div>

    <div class="table-responsive"><table>
        <tr>
            <th>ID</th>
            <th>Ward</th>
            <th>Bed Number</th>
            <th>Occupied</th>
            <th>Assigned Patient</th>
            <th>Actions</th>
        </tr>

        <?php
        $sql = "SELECT b.*, w.ward_name, p.name AS patient_name
                FROM beds b
                LEFT JOIN wards w ON b.ward_id = w.ward_id
                LEFT JOIN patients p ON b.patient_id = p.patient_id";

        $result = $conn->query($sql);
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['bed_id'] ?></td>
            <td><?= $row['ward_name'] ?></td>
            <td><?= $row['bed_number'] ?></td>
            <td><?= $row['is_occupied'] ? 'Yes' : 'No' ?></td>
            <td><?= $row['patient_name'] ?? '-' ?></td>
            <td class="action-buttons">
                <a href="edit.php?id=<?= $row['bed_id'] ?>" class="btn edit-btn">Edit</a>
                <a href="delete.php?id=<?= $row['bed_id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table></div>
</div>




