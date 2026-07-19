<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../../login_doctor.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Prescription.php";

$doctor_id = $_SESSION['user_id'];
$prescriptionService = new Prescription($db);
$result = $prescriptionService->getPrescriptionsByDoctor($doctor_id);
?>

<?php include "../../templates/header.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Prescription History</title>
</head>

<body class="sidebar-page">
    <div class="main-overlay">

<div style="max-width: 1000px; margin: 0 auto;">
    <h2 style="text-align:center;"><i class="fas fa-prescription"></i> Prescription History</h2>

    <?php if ($result->num_rows > 0): ?>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Medicine</th>
                    <th>Dosage</th>
                    <th>Instructions</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= htmlspecialchars($row['medicine_name']) ?></td>
                <td><?= htmlspecialchars($row['dosage']) ?></td>
                <td><?= htmlspecialchars($row['instructions']) ?></td>
                <td><?= htmlspecialchars($row['date_issued']) ?></td>
                <td>
                    <div class="action-buttons">
                        <a href="prescription_edit.php?id=<?= $row['prescription_id'] ?>&patient_id=<?= $row['patient_id'] ?>" class="btn edit-btn"><i class="fas fa-edit"></i> Edit</a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <p class="no-data">No prescriptions found.</p>
    <?php endif; ?>
</div>

    </div>

<?php include "../../templates/footer.php"; ?>
