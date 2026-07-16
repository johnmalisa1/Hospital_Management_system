<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/Medicine.php";
include "../../templates/header.php";

$medicine = new Medicine($db);
?>


    <h2 style="text-align:center;">💊 Medicine Inventory</h2>

    <div class="table-responsive">
        <table>
            <tr style="background:#007bff; color:white;">
                <th>ID</th><th>Name</th><th>Quantity</th><th>Price (Tsh)</th><th>Actions</th>
            </tr>
            <?php
            $result = $medicine->getAllMedicines();
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

