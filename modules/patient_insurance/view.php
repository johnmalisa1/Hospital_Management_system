<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../navbar.php";

$result = $conn->query("
    SELECT pi.*, p.name AS patient_name, ip.provider_name 
    FROM patient_insurance pi
    JOIN patients p ON pi.patient_id = p.patient_id
    JOIN insurance_providers ip ON pi.provider_id = ip.provider_id
    ORDER BY pi.id DESC
");
?>

<style>
    body {
        background: #f4f6f9;
        font-family: Arial, sans-serif;
    }
    h2 {
        text-align: center;
        margin-top: 40px;
    }
    .add-btn {
        display: block;
        width: 220px;
        margin: 20px auto;
        background: #007bff;
        color: white;
        padding: 10px 15px;
        text-align: center;
        text-decoration: none;
        border-radius: 5px;
    }
    table {
        width: 95%;
        margin: 20px auto;
        border-collapse: collapse;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-radius: 10px;
        overflow: hidden;
    }
    th, td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #17a2b8;
        color: white;
        font-size: 16px;
    }
    tr:hover {
        background-color: #f1f1f1;
    }
    a.action {
        color: #007bff;
        margin: 0 5px;
        text-decoration: none;
    }
    a.action:hover {
        text-decoration: underline;
    }
</style>

<h2>🩺 Patient Insurance</h2>
<a class="add-btn" href="add.php">+ Add Insurance Info</a>

<table>
    <tr>
        <th>ID</th>
        <th>Patient Name</th>
        <th>Provider</th>
        <th>Policy Number</th>
        <th>Valid Until</th>
        <th>Actions</th>
    </tr>

    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['patient_name'] ?></td>
            <td><?= $row['provider_name'] ?></td>
            <td><?= $row['policy_number'] ?></td>
            <td><?= $row['valid_until'] ?></td>
            <td>
                <a class="action" href="edit.php?id=<?= $row['id'] ?>">Edit</a> |
                <a class="action" href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this record?')">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
