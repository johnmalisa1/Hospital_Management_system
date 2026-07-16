<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";
require_once __DIR__ . '/../../includes/classes/InsuranceProvider.php';

$insuranceProvider = new InsuranceProvider($db);
$result = $insuranceProvider->getAllProviders();
?>

    <h2 style="text-align:center;">🏢 Insurance Providers</h2>
    <div style="text-align: center; margin-bottom: 20px;">
        <a href="add.php" class="quick-btn">➕ Add Insurance Provider</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Provider Name</th>
                    <th>Contact</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['provider_id'] ?></td>
                    <td><?= htmlspecialchars($row['provider_name']) ?></td>
                    <td><?= htmlspecialchars($row['contact']) ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="edit.php?id=<?= $row['provider_id'] ?>" class="btn edit-btn"><i class="fas fa-edit"></i> Edit</a>
                            <a href="delete.php?id=<?= $row['provider_id'] ?>" onclick="return confirm('Delete this provider?')" class="btn delete-btn"><i class="fas fa-trash"></i> Delete</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../../templates/footer.php"; ?>
