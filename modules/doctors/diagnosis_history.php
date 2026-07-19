<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../../login_doctor.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Diagnosis.php";

$doctor_id = $_SESSION['user_id'];
$diagnosisService = new Diagnosis($db);
$result = $diagnosisService->getDiagnosesByDoctor($doctor_id);
?>
<?php include "../../templates/header.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Diagnosis History</title>
</head>

<body class="sidebar-page">
    <div class="main-overlay">

    <h2 style="text-align:center;"><i class="fas fa-stethoscope"></i> Diagnosis History</h2>

    <?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Diagnosis</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= htmlspecialchars($row['diagnosis']) ?></td>
                <td><?= htmlspecialchars($row['diagnosis_date']) ?></td>
                <td>
                    <div class="action-buttons">
                        <a href="diagnosis_edit.php?id=<?= $row['diagnosis_id'] ?>&patient_id=<?= $row['patient_id'] ?>" class="btn edit-btn"><i class="fas fa-edit"></i> Edit</a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <p class="no-data">No diagnoses found.</p>
    <?php endif; ?>
