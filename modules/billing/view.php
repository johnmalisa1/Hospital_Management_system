<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/Billing.php";
include "../../navbar.php";
?>

<div style="margin-left:230px; padding:20px; max-width:calc(100% - 230px);">
    <h2 style="text-align:center;">📄 Billing Records</h2>

    <div style="overflow-x:auto;">
        <table border="1" cellpadding="10" style="width:100%; background:white; box-shadow:0 0 10px #ccc; border-collapse:collapse;">
            <tr style="background:#007bff; color:white;">
                <th>ID</th><th>Patient</th><th>Amount (Tsh)</th><th>Status</th><th>Actions</th>
            </tr>
            <?php
            $billing = new Billing($db);
            $result = $billing->getAllBills();
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['billing_id']}</td>
                    <td>{$row['patient_name']}</td>
                    <td>{$row['amount']}</td>
                    <td>{$row['status']}</td>
                    <td>
                        <a href='edit.php?id={$row['billing_id']}' style='color:green;'>Edit</a> |
                        <a href='delete.php?id={$row['billing_id']}' style='color:red;' onclick='return confirm(\"Delete this bill?\")'>Delete</a>
                    </td>
                </tr>";
            }
            ?>
        </table>
    </div>
</div>
