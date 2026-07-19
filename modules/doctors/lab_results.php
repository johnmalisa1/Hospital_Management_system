<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../../login_doctor.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/LabTestResult.php";

$doctor_id = $_SESSION['user_id'];
$labTestResult = new LabTestResult($db);
$results = $labTestResult->getResultsByDoctor($doctor_id);
?>

<?php include "../../templates/header.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Lab Results</title>
</head>

<body class="sidebar-page">
    <div class="main-overlay">

<div style="max-width: 1000px; margin: 0 auto;">
    <h2 style="text-align:center;"><i class="fas fa-microscope"></i> Lab Results</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Test</th>
                    <th>Result</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $hasCompleted = false;
            while ($row = $results->fetch_assoc()):
                if ($row['result_text'] === 'Pending') continue;
                $hasCompleted = true;
            ?>
            <tr>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= htmlspecialchars($row['test_name']) ?></td>
                <td><?= htmlspecialchars($row['result_text']) ?></td>
                <td><?= htmlspecialchars($row['result_date']) ?></td>
            </tr>
            <?php endwhile; ?>
            <?php if (!$hasCompleted): ?>
            <tr><td colspan="4" class="no-data">No completed lab results found.</td></tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

    </div>

<?php include "../../templates/footer.php"; ?>
