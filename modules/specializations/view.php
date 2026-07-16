<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";
?>


    <h2 class="page-title">📚 Specializations</h2>
    <div style="text-align: center; margin-bottom: 20px;">
        <a href="add.php" class="quick-btn">+ Add Specialization</a>
    </div>

    <div class="table-responsive"><table>
        <tr>
            <th>ID</th>
            <th>Specialization Name</th>
            <th>Actions</th>
        </tr>

        <?php
        $result = $conn->query("SELECT * FROM specializations ORDER BY specialization_id ASC");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['specialization_id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td class="action-buttons">
                <a href="edit.php?id=<?= $row['specialization_id'] ?>" class="btn edit-btn">Edit</a>
                <a href="delete.php?id=<?= $row['specialization_id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table></div>
</div>




