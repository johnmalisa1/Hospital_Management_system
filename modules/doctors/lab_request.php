<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../../login_doctor.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/LabTestResult.php";
require_once "../../includes/classes/LabTest.php";
require_once "../../includes/classes/Appointment.php";

$doctor_id = $_SESSION['user_id'];
$labTestResult = new LabTestResult($db);
$labTest = new LabTest($db);
$appointment = new Appointment($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'request') {
    $patient_id = intval($_POST['patient_id']);
    $test_id = intval($_POST['test_id']);

    $check = $appointment->getAppointmentsByDoctor($doctor_id);
    $authorized = false;
    while ($r = $check->fetch_assoc()) {
        if ($r['patient_id'] == $patient_id) { $authorized = true; break; }
    }
    if (!$authorized) { echo "Access Denied: Patient not assigned to you."; exit(); }

    $labTestResult->requestLabTest($patient_id, $test_id, $doctor_id);
    header("Location: lab_results.php");
    exit();
}

$patients = $appointment->getPatientsByDoctor($doctor_id);
$tests = $labTest->getAllLabTests();
$requestedTests = $labTestResult->getRequestedTestsByDoctor($doctor_id);
?>
<?php include "../../templates/header.php"; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Laboratory</title>
</head>

<body class="sidebar-page">
    <div class="main-overlay">
    <h2 style="text-align:center;"><i class="fas fa-vials"></i> Request Lab Test</h2>

    <div class="form-container" style="margin-bottom: 30px;">
        <form method="POST">
            <input type="hidden" name="action" value="request">

            <label>Patient:</label>
            <select name="patient_id" required>
                <option value="">-- Select Patient --</option>
                <?php
                $seen = [];
                while ($p = $patients->fetch_assoc()):
                    if (in_array($p['patient_id'], $seen)) continue;
                    $seen[] = $p['patient_id'];
                ?>
                    <option value="<?= $p['patient_id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
                <?php endwhile; ?>
            </select>

            <label>Lab Test:</label>
            <select name="test_id" required>
                <option value="">-- Select Test --</option>
                <?php while ($t = $tests->fetch_assoc()): ?>
                    <option value="<?= $t['test_id'] ?>"><?= htmlspecialchars($t['test_name']) ?> (<?= number_format($t['cost'], 2) ?>)</option>
                <?php endwhile; ?>
            </select>

            <button type="submit">Request Test</button>
        </form>
    </div>

    <h2 style="text-align:center;"><i class="fas fa-list"></i> Requested Tests</h2>
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
            <?php while ($row = $requestedTests->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['patient_name']) ?></td>
                <td><?= htmlspecialchars($row['test_name']) ?></td>
                <td>
                    <?php if ($row['result_text'] === 'Pending'): ?>
                        <span class="badge badge-pending">Pending</span>
                    <?php else: ?>
                        <?= htmlspecialchars($row['result_text']) ?>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['result_date']) ?></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div style="text-align: center; margin-top: 20px;">
        <a href="lab_results.php" class="btn edit-btn"><i class="fas fa-microscope"></i> View Completed Results</a>
    </div>
</div>

    </div>

<?php include "../../templates/footer.php"; ?>
