<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";

$patients = $conn->query("SELECT * FROM patients");
$providers = $conn->query("SELECT * FROM insurance_providers");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("INSERT INTO patient_insurance (patient_id, provider_id, policy_number, valid_until) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiss", $_POST['patient_id'], $_POST['provider_id'], $_POST['policy_number'], $_POST['valid_until']);
    $stmt->execute();
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
    <h2>🩺 Add Patient Insurance</h2>
    <form method="POST">
        <label>Patient</label>
        <select name="patient_id" required>
            <option value="">-- Select Patient --</option>
            <?php while ($p = $patients->fetch_assoc()): ?>
                <option value="<?= $p['patient_id'] ?>"><?= $p['name'] ?></option>
            <?php endwhile; ?>
        </select>

        <label>Insurance Provider</label>
        <select name="provider_id" required>
            <option value="">-- Select Provider --</option>
            <?php while ($pr = $providers->fetch_assoc()): ?>
                <option value="<?= $pr['provider_id'] ?>"><?= $pr['provider_name'] ?></option>
            <?php endwhile; ?>
        </select>

        <label>Policy Number</label>
        <input type="text" name="policy_number" required>

        <label>Valid Until</label>
        <input type="date" name="valid_until" required>

        <button type="submit">Save Insurance</button>
    </form>
</div>
