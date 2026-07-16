<?php
session_start();
include "../../config/db.php";
include "../../templates/header.php";
require_once __DIR__ . '/../../includes/classes/Admission.php';

$admission = new Admission($db);
$result = $admission->getAllAdmissions();
?>


    <h2 style="text-align:center;">📋 Patient Admissions</h2>

    <div class="table-responsive">
        <table>
            <tr style="background:#007bff; color:white;">
                <th>ID</th><th>Patient</th><th>Room</th><th>Date</th><th>Actions</th>
            </tr>
            <?php
                        while ($row = $result->fetch_assoc()) {
                echo "<tr>
                    <td>{$row['admission_id']}</td>
                    <td>{$row['patient_name']}</td>
                    <td>{$row['room_number']}</td>
                    <td>{$row['admission_date']}</td>
                    <td>
                        <a href='edit.php?id={$row['admission_id']}' style='color:green;'>Edit</a> |
                        <a href='delete.php?id={$row['admission_id']}' style='color:red;' onclick='return confirm(\"Delete this admission?\")'>Delete</a>
                    </td>
                </tr>";
            }
            ?>
        </table>
    </div>
</div>

