<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Payment.php";
include "../../templates/header.php";

$payment = new Payment($db);
$result = $payment->getAllPayments();
?>

    <h2 style="text-align:center;">💰 Payments</h2>
    <div style="text-align: center; margin-bottom: 20px;">
        <a href="add.php" class="quick-btn">➕ Add Payment</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Bill ID</th>
                    <th>Amount Paid</th>
                    <th>Payment Date</th>
                    <th>Method</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['payment_id'] ?></td>
                    <td>#<?= $row['billing_id'] ?> (Tsh <?= number_format($row['bill_amount'], 2) ?>)</td>
                    <td>Tsh <?= number_format($row['amount_paid'], 2) ?></td>
                    <td><?= htmlspecialchars($row['payment_date']) ?></td>
                    <td><?= htmlspecialchars($row['method']) ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="edit.php?id=<?= $row['payment_id'] ?>" class="btn edit-btn"><i class="fas fa-edit"></i> Edit</a>
                            <a href="delete.php?id=<?= $row['payment_id'] ?>" onclick="return confirm('Delete this payment?')" class="btn delete-btn"><i class="fas fa-trash"></i> Delete</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../../templates/footer.php"; ?>
