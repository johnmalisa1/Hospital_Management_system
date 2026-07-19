<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../../login_doctor.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Vaccination.php";
require_once "../../includes/classes/Appointment.php";

$doctor_id = $_SESSION['user_id'];
$vaccinationService = new Vaccination($db);
$appointment = new Appointment($db);
$patients = $appointment->getPatientsByDoctor($doctor_id);

$allVaccinations = [];
while ($p = $patients->fetch_assoc()):
    $vaccs = $vaccinationService->getVaccinationsByPatient($p['patient_id']);
    while ($v = $vaccs->fetch_assoc()):
        $allVaccinations[] = $v;
    endwhile;
endwhile;
?>

<?php include "../../templates/header.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Vaccinations</title>
</head>

<body class="sidebar-page">
    <div class="main-overlay">

<div style="max-width: 1000px; margin: 0 auto;">
    <h2 style="text-align:center;"><i class="fas fa-syringe"></i> Vaccination Records</h2>

            <?php if (count($allVaccinations) > 0): ?>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Vaccine</th>
                    <th>Date Administered</th>
                    <th>Dose #</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($allVaccinations as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= htmlspecialchars($row['vaccine_name']) ?></td>
                <td><?= htmlspecialchars($row['vaccination_date']) ?></td>
                <td><?= htmlspecialchars($row['dose_number']) ?></td>
                <td>
                    <div class="action-buttons">
                        <a href="vaccination_edit.php?id=<?= $row['vaccination_id'] ?>&patient_id=<?= $row['patient_id'] ?>" class="btn edit-btn"><i class="fas fa-edit"></i> Edit</a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
    <p class="no-data">No vaccination records found.</p>
    <?php endif; ?>

    <div style="text-align: center; margin-top: 20px;">
        <a href="vaccination_add.php" class="btn edit-btn"><i class="fas fa-plus"></i> Add Vaccination</a>
    </div>
</div>

    </div>

<?php include "../../templates/footer.php"; ?>
