<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";
require_once __DIR__ . '/../../includes/classes/BloodBank.php';

$bloodBank = new BloodBank($db);
$result = $bloodBank->getAllUnits();
?>


    <h2 class="page-title">🩸 Blood Bank</h2>
    <div style="text-align: center; margin-bottom: 20px;">
        <a href="add.php" class="quick-btn">+ Add Blood Unit</a>
    </div>

    <div class="table-responsive"><table>
        <tr>
            <th>ID</th>
            <th>Blood Type</th>
            <th>Quantity</th>
            <th>Donor</th>
            <th>Donated</th>
            <th>Expires</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['unit_id'] ?></td>
            <td><?= $row['blood_type'] ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= $row['donor_name'] ?></td>
            <td><?= $row['date_donated'] ?></td>
            <td><?= $row['expiry_date'] ?></td>
            <td><?= $row['status'] ?></td>
            <td class="action-buttons">
                <a href="edit.php?id=<?= $row['unit_id'] ?>" class="btn edit-btn">Edit</a>
                <a href="delete.php?id=<?= $row['unit_id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table></div>
</div>




