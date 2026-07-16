<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/LabTest.php";
include "../../templates/header.php";

$labTest = new LabTest($db);
?>


    <h2 style="text-align:center;">🧪 Lab Tests</h2>

    <div class="table-responsive">
        <table>
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

