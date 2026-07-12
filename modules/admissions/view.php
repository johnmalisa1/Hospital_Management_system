<?php
session_start();
include "../../config/db.php";
include "../../navbar.php";
require_once __DIR__ . '/../../includes/classes/Admission.php';

$admission = new Admission($db);
$result = $admission->getAllAdmissions();
?>

<div style="margin-left:230px; padding:20px; max-width:calc(100% - 230px);">
    <h2 style="text-align:center;">📋 Patient Admissions</h2>

    <div style="overflow-x:auto;">
        <table border="1" cellpadding="10" style="width:100%; background:white; box-shadow:0 0 10px #ccc; border-collapse:collapse;">
            <tr style="background:#007bff; color:white;">
                <th>ID</th><th>Patient</th><th>Room</th><th>Date</th><th>Actions</th>
            </tr>
            <?php
            $sql = "SELECT a.admission_id, p.name AS patient_name, r.room_number, a.admission_date
                    FROM admissions a
                    JOIN patients p ON a.patient_id = p.patient_id
                    JOIN rooms r ON a.room_id = r.room_id
                    ORDER BY a.admission_id DESC";
            $result = $conn->query($sql);
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
