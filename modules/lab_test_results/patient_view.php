<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Patient') {
    header("Location: ../../login.php");
    exit();
}

include "../../config/db.php";
require_once "../../includes/classes/LabTestResult.php";
require_once "../../includes/classes/User.php";
include "../../templates/header.php";

// Get the logged-in patient's ID
$username = $_SESSION['username'];
$labTestResult = new LabTestResult($db);
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
    <h2 style="text-align:center;"><i class="fas fa-microscope"></i> My Lab Test Results</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Test Name</th>
                    <th>Doctor</th>
                    <th>Result</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $results = $labTestResult->getResultsByPatient($patient_id);

            while ($row = $results->fetch_assoc()):
            ?>
            <tr>
                <td><?= htmlspecialchars($row['test_name']) ?></td>
                <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                <td><?= htmlspecialchars($row['result_text']) ?></td>
                <td><?= htmlspecialchars($row['result_date']) ?></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../../templates/footer.php"; ?>
</body>
</html>
