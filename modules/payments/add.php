<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Payment.php";

$payment = new Payment($db);

$billings = $payment->getBillings();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payment->addPayment($_POST['billing_id'], $_POST['amount_paid'], $_POST['payment_date'], $_POST['method']);
    header("Location: view.php");
}
?>

<div class="form-container">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Payments</a>
    <h2>💳 Add Payment</h2>
    <form method="POST">
        <label>Billing ID</label>
        <select name="billing_id" required>
            <?php while($row = $billings->fetch_assoc()): ?>
                <option value="<?= $row['billing_id'] ?>">#<?= $row['billing_id'] ?> - Tsh <?= $row['amount'] ?></option>
            <?php endwhile; ?>
        </select>

        <label>Amount Paid</label>
        <input type="number" step="0.01" name="amount_paid" required>

        <label>Payment Date</label>
        <input type="date" name="payment_date" required>

        <label>Payment Method</label>
        <input type="text" name="method" placeholder="e.g., Cash, Card">

        <button type="submit">Save Payment</button>
    </form>
</div>
