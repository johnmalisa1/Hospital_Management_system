<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../../login.php"); exit(); }
include "../../config/db.php";
require_once "../../includes/classes/Prescription.php";
include "../../navbar.php";
?>

<h2 style="text-align:center;">Prescriptions</h2>

<div style="text-align:center; margin: 20px;">
    <a href="add.php" style="background:#007bff;color:white;padding:10px 20px;border-radius:5px;text-decoration:none;">+ Add Prescription</a>
</div>

<table style="width: 95%; margin: auto; background: white; border-collapse: collapse; box-shadow: 0 0 10px #ccc;">
    <tr style="background: #007bff; color: white;">
        <th>ID</th><th>Patient</th><th>Doctor</th><th>Medicine</th><th>Dosage</th><th>Instructions</th><th>Date</th><th>Actions</th>
    </tr>
    <?php
    $prescription = new Prescription($db);
    $res = $prescription->getAllPrescriptions();
    while ($row = $res->fetch_assoc()):
    ?>
    <tr>
        <td><?= $row['prescription_id'] ?></td>
        <td><?= $row['patient_name'] ?></td>
        <td><?= $row['doctor_name'] ?></td>
        <td><?= $row['medicine_name'] ?></td>
        <td><?= $row['dosage'] ?></td>
        <td><?= $row['instructions'] ?></td>
        <td><?= $row['date_issued'] ?></td>
        <td>
            <a href="edit.php?id=<?= $row['prescription_id'] ?>" style="color:green;">Edit</a> |
            <a href="delete.php?id=<?= $row['prescription_id'] ?>" style="color:red;" onclick="return confirm('Delete?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
