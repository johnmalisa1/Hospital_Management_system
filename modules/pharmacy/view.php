<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/Pharmacy.php";
include "../../navbar.php";

$pharmacy = new Pharmacy($db);
?>

<div style="margin-left:230px; padding:20px; max-width:calc(100% - 230px);">
    <h2 style="text-align:center;">💊 Medicine Inventory</h2>

    <div style="overflow-x:auto;">
        <table border="1" cellpadding="10" style="width:100%; background:white; box-shadow:0 0 10px #ccc; border-collapse:collapse;">
            <tr style="background:#007bff; color:white;">
                <th>ID</th><th>Name</th><th>Quantity</th><th>Price (Tsh)</th><th>Actions</th>
            </tr>
            <?php
            $result = $pharmacy->getMedicineStock();
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['medicine_id']}</td>
                    <td>{$row['name']}</td>
                    <td>{$row['quantity']}</td>
                    <td>{$row['price']}</td>
                    <td>
                        <a href='edit.php?id={$row['medicine_id']}' style='color:green;'>Edit</a> |
                        <a href='delete.php?id={$row['medicine_id']}' style='color:red;' onclick='return confirm(\"Delete this?\")'>Delete</a>
                    </td>
                </tr>";
            }
            ?>
        </table>
    </div>
</div>
