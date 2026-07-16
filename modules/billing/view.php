<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/Billing.php";
include "../../templates/header.php";
?>


    <h2 style="text-align:center;">📄 Billing Records</h2>

    <div class="table-responsive">
        <table>
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

