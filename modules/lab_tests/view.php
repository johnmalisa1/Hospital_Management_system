<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/LabTest.php";
include "../../navbar.php";

$labTest = new LabTest($db);
?>

<div style="margin-left:230px; padding:20px; max-width:calc(100% - 230px);">
    <h2 style="text-align:center;">🧪 Lab Tests</h2>

    <div style="overflow-x:auto;">
        <table border="1" cellpadding="10" style="width:100%; background:white; box-shadow:0 0 10px #ccc; border-collapse:collapse;">
            <tr style="background:#007bff; color:white;">
                <th>ID</th><th>Test Name</th><th>Cost</th><th>Actions</th>
            </tr>
            <?php
            $result = $labTest->getLabTests();
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['test_id']}</td>
                    <td>{$row['test_name']}</td>
                    <td>{$row['cost']}</td>
                    <td>
                        <a href='edit.php?id={$row['test_id']}' style='color:green;'>Edit</a> |
                        <a href='delete.php?id={$row['test_id']}' style='color:red;' onclick='return confirm(\"Are you sure?\")'>Delete</a>
                    </td>
                </tr>";
            }
            ?>
        </table>
    </div>
</div>
