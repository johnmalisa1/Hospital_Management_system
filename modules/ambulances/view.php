<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";
require_once __DIR__ . '/../../includes/classes/Ambulance.php';

$ambulance = new Ambulance($db);
$result = $ambulance->getAllAmbulances();
?>

<div class="main-content">
    <h2 class="page-title">🚑 Ambulance Management</h2>
    <div class="center-btn">
        <a href="add.php" class="quick-btn">+ Add Ambulance</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Vehicle No.</th>
            <th>Driver</th>
            <th>Contact</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['ambulance_id'] ?></td>
            <td><?= $row['vehicle_number'] ?></td>
            <td><?= $row['driver_name'] ?></td>
            <td><?= $row['contact_number'] ?></td>
            <td><?= $row['availability'] ?></td>
            <td class="action-buttons">
                <a href="edit.php?id=<?= $row['ambulance_id'] ?>" class="btn edit-btn">Edit</a>
                <a href="delete.php?id=<?= $row['ambulance_id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
