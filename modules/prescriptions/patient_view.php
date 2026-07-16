<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Patient') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Prescription.php";
require_once "../../includes/classes/User.php";
include "../../templates/header.php";

// Get patient's ID based on session username
$username = $_SESSION['username'];
$prescription = new Prescription($db);
$userService = new User($db);
$patient_id = $userService->getPatientIdByUsername($username);
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="main-content">
    <a href="../../patient_dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    <h2 style="text-align:center;"><i class="fas fa-prescription"></i> My Prescriptions</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Doctor</th><th>Medicine</th><th>Dosage</th><th>Instructions</th><th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $res = $prescription->getPrescriptionsByPatient($patient_id);

            while ($row = $res->fetch_assoc()):
            ?>
            <tr>
                <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                <td><?= htmlspecialchars($row['medicine_name']) ?></td>
                <td><?= htmlspecialchars($row['dosage']) ?></td>
                <td><?= htmlspecialchars($row['instructions']) ?></td>
                <td><?= htmlspecialchars($row['date_issued']) ?></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../../templates/footer.php"; ?>
</body>
</html>
