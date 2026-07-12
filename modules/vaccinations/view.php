<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/Vaccination.php";
include "../../templates/header.php";
?>

<div class="main-content">
    <h2 class="page-title">💉 Vaccination Records</h2>
    <div class="center-btn">
        <a href="add.php" class="quick-btn">+ Add Vaccination</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Patient</th>
            <th>Vaccine</th>
            <th>Date</th>
            <th>Notes</th>
            <th>Actions</th>
        </tr>

        <?php
        $vaccination = new Vaccination($db);
        $result = $vaccination->getAllVaccinations();
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['vaccination_id'] ?></td>
            <td><?= $row['patient_name'] ?></td>
            <td><?= $row['vaccine_name'] ?></td>
            <td><?= $row['date_administered'] ?></td>
            <td><?= $row['notes'] ?></td>
            <td class="action-buttons">
                <a href="edit.php?id=<?= $row['vaccination_id'] ?>" class="btn edit-btn">Edit</a>
                <a href="delete.php?id=<?= $row['vaccination_id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
