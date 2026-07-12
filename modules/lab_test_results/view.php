<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../../login.php"); exit(); }
include "../../config/db.php";
require_once "../../includes/classes/LabTestResult.php";
include "../../navbar.php";
?>

<h2 style="text-align:center;">Lab Test Results</h2>

<div style="text-align:center; margin: 20px;">
    <a href="add.php" style="background:#007bff;color:white;padding:10px 20px;border-radius:5px;text-decoration:none;">+ Add Result</a>
</div>

<table style="width: 90%; margin: auto; background: white; border-collapse: collapse; box-shadow: 0 0 8px #ccc;">
    <tr style="background:#007bff;color:white;">
        <th>ID</th>
        <th>Patient</th>
        <th>Test</th>
        <th>Doctor</th>
        <th>Result</th>
        <th>Date</th>
        <th>Actions</th>
    </tr>
    <?php
    $labTestResult = new LabTestResult($db);
    $res = $labTestResult->getAllResults();
    while ($row = $res->fetch_assoc()):
    ?>
    <tr>
        <td><?= $row['result_id'] ?></td>
        <td><?= $row['patient_name'] ?></td>
        <td><?= $row['test_name'] ?></td>
        <td><?= $row['doctor_name'] ?></td>
        <td><?= $row['result_text'] ?></td>
        <td><?= $row['result_date'] ?></td>
        <td>
            <a href="edit.php?id=<?= $row['result_id'] ?>" style="color:green;">Edit</a> |
            <a href="delete.php?id=<?= $row['result_id'] ?>" onclick="return confirm('Delete?')" style="color:red;">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
