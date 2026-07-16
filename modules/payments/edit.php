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

<div class="form-container">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Payments</a>
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
