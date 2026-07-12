<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Patient') {
    header("Location: ../../login.php");
    exit();
}

include "../../config/db.php";
require_once "../../includes/classes/LabTestResult.php";
include "../../navbar.php";

// Get the logged-in patient's ID
$username = $_SESSION['username'];
$labTestResult = new LabTestResult($db);
$patient_id = $labTestResult->getPatientIdByUsername($username);
?>

<h2 style="text-align:center;">My Lab Test Results</h2>

<table style="width:90%; margin:auto; background:white; border-collapse:collapse; box-shadow: 0 0 8px #ccc;">
    <tr style="background:#007bff; color:white;">
        <th>Test Name</th>
        <th>Doctor</th>
        <th>Result</th>
        <th>Date</th>
    </tr>

    <?php
    $results = $labTestResult->getResultsByPatient($patient_id);

    while ($row = $results->fetch_assoc()):
    ?>
    <tr>
        <td><?= $row['test_name'] ?></td>
        <td><?= $row['doctor_name'] ?></td>
        <td><?= $row['result_text'] ?></td>
        <td><?= $row['result_date'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>
