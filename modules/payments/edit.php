<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Payment.php";

$id = $_GET['id'];
$payment = new Payment($db);
$row = $payment->getPaymentById($id);
$billings = $payment->getBillings();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment->updatePayment($id, $_POST['billing_id'], $_POST['amount_paid'], $_POST['payment_date'], $_POST['method']);
    header("Location: view.php");
}
?>

<style>
    body {
        background: #f4f6f9;
        font-family: Arial, sans-serif;
    }
    .form-container {
        width: 500px;
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
        background: #28a745;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        width: 100%;
        font-size: 16px;
    }
</style>

<div class="form-container">
    <h2>✏️ Edit Payment</h2>
    <form method="POST">
        <label>Billing ID</label>
        <select name="billing_id" required>
            <?php while ($b = $billings->fetch_assoc()): ?>
                <option value="<?= $b['billing_id'] ?>" <?= ($b['billing_id'] == $row['billing_id']) ? 'selected' : '' ?>>
                    #<?= $b['billing_id'] ?> - Tsh <?= $b['amount'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Amount Paid</label>
        <input type="number" name="amount_paid" value="<?= $row['amount_paid'] ?>" required>

        <label>Payment Date</label>
        <input type="date" name="payment_date" value="<?= $row['payment_date'] ?>" required>

        <label>Payment Method</label>
        <input type="text" name="method" value="<?= $row['method'] ?>" required>

        <button type="submit">Update Payment</button>
    </form>
</div>
