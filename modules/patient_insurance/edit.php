<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once __DIR__ . '/../../includes/classes/InsuranceProvider.php';
require_once __DIR__ . '/../../includes/classes/PatientInsurance.php';

$patientInsurance = new PatientInsurance($db);
$insuranceProvider = new InsuranceProvider($db);

$id = $_GET['id'];
$row = $patientInsurance->getInsuranceById($id);

$patients = $conn->query("SELECT * FROM patients");
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

<style>
    body {
        background: #f4f6f9;
        font-family: Arial, sans-serif;
    }
    .form-container {
        width: 550px;
        margin: 50px auto;
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        margin-bottom: 25px;
    }
    label {
        display: block;
        margin-bottom: 6px;
        font-weight: bold;
    }
    input, select {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }
    button {
        background: #17a2b8;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        width: 100%;
        font-size: 16px;
    }
</style>

<div class="form-container">
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
