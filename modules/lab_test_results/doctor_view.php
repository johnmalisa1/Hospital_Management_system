<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../../login.php");
    exit();
}

include "../../config/db.php";
require_once "../../includes/classes/LabTestResult.php";
include "../../templates/header.php";

$doctor_id = $_SESSION['user_id'];
$labTestResult = new LabTestResult($db);
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="main-content">
    <a href="../../doctor_dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    <h2 style="text-align:center;"><i class="fas fa-microscope"></i> My Patients' Lab Test Results</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Patient</th>
                    <th>Test</th>
                    <th>Result</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $results = $labTestResult->getResultsByDoctor($doctor_id);

            while ($row = $results->fetch_assoc()):
            ?>
            <tr>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= htmlspecialchars($row['test_name']) ?></td>
                <td><?= htmlspecialchars($row['result_text']) ?></td>
                <td><?= htmlspecialchars($row['result_date']) ?></td>
                <td>
                    <div class="action-buttons">
                        <a href="edit.php?id=<?= $row['result_id'] ?>" class="btn edit-btn"><i class="fas fa-edit"></i> Edit</a>
                        <a href="delete.php?id=<?= $row['result_id'] ?>" onclick="return confirm('Delete result?')" class="btn delete-btn"><i class="fas fa-trash"></i> Delete</a>
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
