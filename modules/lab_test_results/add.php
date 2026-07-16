<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Admin' && $_SESSION['role'] !== 'Doctor')) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/LabTest.php";
require_once "../../includes/classes/LabTestResult.php";
require_once "../../includes/classes/Patient.php";
require_once "../../includes/classes/User.php";

$labTestResult = new LabTestResult($db);
$labTest = new LabTest($db);
$patient = new Patient($db);
$userService = new User($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $test_id = $_POST['test_id'];
    $doctor_id = $_POST['doctor_id'];
    $result_text = $_POST['result_text'];
    $result_date = $_POST['result_date'];

    $labTestResult->addResult($patient_id, $test_id, $doctor_id, $result_text, $result_date);
    header("Location: view.php");
}

$patients = $patient->getAllPatients();
$tests = $labTest->getAllLabTests();
$doctors = $userService->getDoctorUserAccounts();
?>
<a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to lab_test_results</a>
<h2 style="text-align:center;">Add Lab Test Result</h2>
<form method="POST" style="width:500px;margin:auto;padding:30px;background:white;border-radius:10px;box-shadow:0 0 10px #ccc;">
    <label>Patient:</label>
    <select name="patient_id" required>
        <option value="">-- Select --</option>
        <?php while ($p = $patients->fetch_assoc()): ?>
            <option value="<?= $p['patient_id'] ?>"><?= $p['name'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Test:</label>
    <select name="test_id" required>
        <option value="">-- Select --</option>
        <?php while ($t = $tests->fetch_assoc()): ?>
            <option value="<?= $t['test_id'] ?>"><?= $t['test_name'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Doctor:</label>
    <select name="doctor_id" required>
        <option value="">-- Select --</option>
        <?php while ($d = $doctors->fetch_assoc()): ?>
            <option value="<?= $d['id'] ?>"><?= $d['username'] ?></option>
        <?php endwhile; ?>
    </select><br><br>

    <label>Result Text:</label>
    <textarea name="result_text" required style="width:100%; height:80px;"></textarea><br><br>

    <label>Result Date:</label>
    <input type="date" name="result_date" required style="width:100%; padding:10px;"><br><br>

    <button type="submit" style="background:#28a745;color:white;padding:10px 20px;border:none;">Save</button>
</form>

