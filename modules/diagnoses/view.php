<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/Diagnosis.php";
include "../../templates/header.php";
?>


    <h2 class="page-title">🩺 Patient Diagnoses</h2>
    <div style="text-align: center; margin-bottom: 20px;">
        <a href="add.php" class="quick-btn">+ Add Diagnosis</a>
    </div>

    <div class="table-responsive"><table>
        <tr>
            <th>ID</th>
            <th>Patient</th>
            <th>Diagnosis</th>
            <th>Date</th>
            <th>Doctor</th>
            <th>Actions</th>
        </tr>

        <?php
        $diagnosisService = new Diagnosis($db);
        $result = $diagnosisService->getAllDiagnoses();
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['diagnosis_id'] ?></td>
            <td><?= $row['patient_name'] ?></td>
            <td><?= $row['diagnosis'] ?></td>
            <td><?= $row['diagnosis_date'] ?></td>
            <td><?= $row['doctor_name'] ?></td>
            <td class="action-buttons">
                <a href="edit.php?id=<?= $row['diagnosis_id'] ?>" class="btn edit-btn">Edit</a>
                <a href="delete.php?id=<?= $row['diagnosis_id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table></div>
</div>




