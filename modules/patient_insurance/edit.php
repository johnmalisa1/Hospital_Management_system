<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once __DIR__ . '/../../includes/classes/InsuranceProvider.php';
require_once __DIR__ . '/../../includes/classes/PatientInsurance.php';
require_once __DIR__ . '/../../includes/classes/Patient.php';

$patientInsurance = new PatientInsurance($db);
$insuranceProvider = new InsuranceProvider($db);

$id = $_GET['id'];
$row = $patientInsurance->getInsuranceById($id);

$patient = new Patient($db);
$patients = $patient->getAllPatients();
$providers = $insuranceProvider->getAllProviders();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patientInsurance->updateInsurance(
        $id,
        $_POST['patient_id'],
        $_POST['provider_id'],
        $_POST['policy_number'],
        $_POST['valid_until']
    );
    header("Location: view.php");
}
?>

<div class="form-container">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Patient Insurance</a>
    <h2>✏️ Edit Patient Insurance</h2>
    <form method="POST">
        <label>Patient</label>
        <select name="patient_id" required>
            <?php while ($p = $patients->fetch_assoc()): ?>
                <option value="<?= $p['patient_id'] ?>" <?= $p['patient_id'] == $row['patient_id'] ? 'selected' : '' ?>>
                    <?= $p['name'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Insurance Provider</label>
        <select name="provider_id" required>
            <?php while ($pr = $providers->fetch_assoc()): ?>
                <option value="<?= $pr['provider_id'] ?>" <?= $pr['provider_id'] == $row['provider_id'] ? 'selected' : '' ?>>
                    <?= $pr['provider_name'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Policy Number</label>
        <input type="text" name="policy_number" value="<?= $row['policy_number'] ?>" required>

        <label>Valid Until</label>
        <input type="date" name="valid_until" value="<?= $row['valid_until'] ?>" required>

        <button type="submit">Update Insurance</button>
    </form>
</div>
