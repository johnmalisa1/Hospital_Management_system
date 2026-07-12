<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Prescription.php";
include "../../navbar.php";

$doctor_id = $_SESSION['user_id'];
$prescription = new Prescription($db);
?>

<h2 style="text-align:center;">My Issued Prescriptions</h2>

<table style="width: 90%; margin: auto; background: white; border-collapse: collapse; box-shadow: 0 0 10px #ccc;">
    <tr style="background: #007bff; color: white;">
        <th>Patient</th><th>Medicine</th><th>Dosage</th><th>Instructions</th><th>Date</th><th>Actions</th>
    </tr>
    <?php
    $res = $prescription->getPrescriptionsByDoctor($doctor_id);

    while ($row = $res->fetch_assoc()):
    ?>
    <tr>
        <td><?= $row['patient_name'] ?></td>
        <td><?= $row['medicine_name'] ?></td>
        <td><?= $row['dosage'] ?></td>
        <td><?= $row['instructions'] ?></td>
        <td><?= $row['date_issued'] ?></td>
        <td>
            <a href="edit.php?id=<?= $row['prescription_id'] ?>" style="color:green;">Edit</a> |
            <a href="delete.php?id=<?= $row['prescription_id'] ?>" onclick="return confirm('Delete?')" style="color:red;">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
