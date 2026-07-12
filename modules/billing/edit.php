<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/Billing.php";
require_once "../../includes/classes/Patient.php";
include "../../navbar.php";

$id = $_GET['id'];
$billing = new Billing($db);
$patient = new Patient($db);
$row = $billing->getBillById($id);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $patient_id = $_POST['patient_id'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];

    $billing->updateBill($id, $patient_id, $amount, $status);
    header("Location: view.php");
    exit();
}
?>

<h2 style="text-align:center;">✏️ Edit Billing</h2>
<form method="POST" style="width:400px; margin:auto; padding:20px; background:white; border-radius:10px; box-shadow:0 0 10px #ccc;">
    <label>Patient:</label>
    <select name="patient_id" required style="width:100%; padding:10px;">
        <?php
        $patients = $patient->getAllPatients();
        while ($p = $patients->fetch_assoc()) {
            $sel = $p['patient_id'] == $row['patient_id'] ? "selected" : "";
            echo "<option value='{$p['patient_id']}' $sel>{$p['name']}</option>";
        }
        ?>
    </select><br><br>

    <label>Amount (Tsh):</label>
    <input type="number" step="0.01" name="amount" value="<?= $row['amount'] ?>" required style="width:100%; padding:10px;"><br><br>

    <label>Status:</label>
    <select name="status" required style="width:100%; padding:10px;">
        <option value="Paid" <?= $row['status'] == 'Paid' ? 'selected' : '' ?>>Paid</option>
        <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
    </select><br><br>

    <button type="submit" style="padding:10px 20px; background:#007bff; color:white; border:none;">Update Billing</button>
</form>
