<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Patient') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Prescription.php";
include "../../navbar.php";

// Get patient's ID based on session username
$username = $_SESSION['username'];
$prescription = new Prescription($db);
$patient_id = $prescription->getPatientIdByUsername($username);
?>

<h2 style="text-align:center;">My Prescriptions</h2>

<table style="width: 90%; margin: auto; background: white; border-collapse: collapse; box-shadow: 0 0 10px #ccc;">
    <tr style="background: #007bff; color: white;">
        <th>Doctor</th><th>Medicine</th><th>Dosage</th><th>Instructions</th><th>Date</th>
    </tr>
    <?php
    $res = $prescription->getPrescriptionsByPatient($patient_id);

    while ($row = $res->fetch_assoc()):
    ?>
    <tr>
        <td><?= $row['doctor_name'] ?></td>
        <td><?= $row['medicine_name'] ?></td>
        <td><?= $row['dosage'] ?></td>
        <td><?= $row['instructions'] ?></td>
        <td><?= $row['date_issued'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>
