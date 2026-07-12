<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../../login.php");
    exit();
}

include "../../config/db.php";
require_once "../../includes/classes/LabTestResult.php";
include "../../navbar.php";

$doctor_id = $_SESSION['user_id'];
$labTestResult = new LabTestResult($db);
?>

<h2 style="text-align:center;">My Patients' Lab Test Results</h2>

<table style="width:90%; margin:auto; background:white; border-collapse:collapse; box-shadow: 0 0 8px #ccc;">
    <tr style="background:#007bff; color:white;">
        <th>Patient</th>
        <th>Test</th>
        <th>Result</th>
        <th>Date</th>
        <th>Actions</th>
    </tr>

    <?php
    $results = $labTestResult->getResultsByDoctor($doctor_id);

    while ($row = $results->fetch_assoc()):
    ?>
    <tr>
        <td><?= $row['patient_name'] ?></td>
        <td><?= $row['test_name'] ?></td>
        <td><?= $row['result_text'] ?></td>
        <td><?= $row['result_date'] ?></td>
        <td>
            <a href="edit.php?id=<?= $row['result_id'] ?>" style="color:green;">Edit</a> |
            <a href="delete.php?id=<?= $row['result_id'] ?>" onclick="return confirm('Delete result?')" style="color:red;">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
