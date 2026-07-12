<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";
require_once __DIR__ . '/../../includes/classes/Equipment.php';

$equipment = new Equipment($db);
$result = $equipment->getAllEquipment();
?>


<div class="main-content">
    <h2 class="page-title">🏥 Equipment & Assets</h2>
    <div class="center-btn">
        <a href="add.php" class="quick-btn">+ Add Equipment</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Type</th>
            <th>Quantity</th>
            <th>Status</th>
            <th>Purchased</th>
            <th>Actions</th>
        </tr>

        <?php
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['equipment_id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['type'] ?></td>
            <td><?= $row['quantity'] ?></td>
            <td><?= $row['status'] ?></td>
            <td><?= $row['purchase_date'] ?></td>
            <td class="action-buttons">
                <a href="edit.php?id=<?= $row['equipment_id'] ?>" class="btn edit-btn">Edit</a>
                <a href="delete.php?id=<?= $row['equipment_id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
