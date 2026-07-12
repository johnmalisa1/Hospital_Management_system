<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/Billing.php";
require_once "../../includes/classes/Patient.php";
include "../../navbar.php";

$billing = new Billing($db);
$patient = new Patient($db);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $patient_id = $_POST['patient_id'];
    $amount = $_POST['amount'];
    $status = $_POST['status'];

    $billing->createBill($patient_id, $amount, $status);
    header("Location: view.php");
    exit();
}
?>

<h2 style="text-align:center;">➕ Add Billing</h2>
<form method="POST" style="width:400px; margin:auto; padding:20px; background:white; border-radius:10px; box-shadow:0 0 10px #ccc;">
    <label>Patient:</label>
    <select name="patient_id" required style="width:100%; padding:10px;">
        <option value="">-- Select Patient --</option>
        <?php
        $patients = $patient->getAllPatients();
        while ($p = $patients->fetch_assoc()) {
            echo "<option value='{$p['patient_id']}'>{$p['name']}</option>";
        }
        ?>
    </select><br><br>

    <label>Amount (Tsh):</label>
    <input type="number" step="0.01" name="amount" required style="width:100%; padding:10px;"><br><br>

    <label>Status:</label>
    <select name="status" required style="width:100%; padding:10px;">
        <option value="Paid">Paid</option>
        <option value="Pending">Pending</option>
    </select><br><br>

    <button type="submit" style="padding:10px 20px; background:#28a745; color:white; border:none;">Add Bill</button>
</form>
