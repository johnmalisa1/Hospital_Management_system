<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";
require_once __DIR__ . '/../../includes/classes/Ward.php';

$ward = new Ward($db);
$result = $ward->getAllWards();
?>


    <a href="../../dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    <h2 style="text-align:center;"><i class="fas fa-hospital"></i> All Wards</h2>

    <div style="text-align: center; margin-bottom: 20px;">
        <a href="add.php" class="quick-btn"><i class="fas fa-plus"></i> Add Ward</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ward Name</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['ward_id'] ?></td>
                    <td><?= htmlspecialchars($row['ward_name']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="edit.php?id=<?= $row['ward_id'] ?>" class="btn edit-btn"><i class="fas fa-edit"></i> Edit</a>
                            <a href="delete.php?id=<?= $row['ward_id'] ?>" onclick="return confirm('Delete this ward?')" class="btn delete-btn"><i class="fas fa-trash"></i> Delete</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../../templates/footer.php"; ?>

