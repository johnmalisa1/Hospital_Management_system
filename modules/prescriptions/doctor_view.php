<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Prescription.php";
include "../../templates/header.php";

$doctor_id = $_SESSION['user_id'];
$prescription = new Prescription($db);
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="main-content">
    <a href="../../doctor_dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    <h2 style="text-align:center;"><i class="fas fa-prescription"></i> My Issued Prescriptions</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Patient</th><th>Medicine</th><th>Dosage</th><th>Instructions</th><th>Date</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $res = $prescription->getPrescriptionsByDoctor($doctor_id);

            while ($row = $res->fetch_assoc()):
            ?>
            <tr>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= htmlspecialchars($row['medicine_name']) ?></td>
                <td><?= htmlspecialchars($row['dosage']) ?></td>
                <td><?= htmlspecialchars($row['instructions']) ?></td>
                <td><?= htmlspecialchars($row['date_issued']) ?></td>
                <td>
                    <div class="action-buttons">
                        <a href="edit.php?id=<?= $row['prescription_id'] ?>" class="btn edit-btn"><i class="fas fa-edit"></i> Edit</a>
                        <a href="delete.php?id=<?= $row['prescription_id'] ?>" onclick="return confirm('Delete?')" class="btn delete-btn"><i class="fas fa-trash"></i> Delete</a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../../templates/footer.php"; ?>
</body>
</html>
