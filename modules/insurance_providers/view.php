<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../navbar.php";

$result = $conn->query("SELECT * FROM insurance_providers ORDER BY provider_id DESC");
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
        width: 90%;
        margin: 20px auto;
        border-collapse: collapse;
        background: white;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        border-radius: 10px;
        overflow: hidden;
    }
    th, td {
        padding: 14px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #343a40;
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

<h2>🏢 Insurance Providers</h2>
<a class="add-btn" href="add.php">+ Add Insurance Provider</a>

<table>
    <tr>
        <th>ID</th>
        <th>Provider Name</th>
        <th>Contact</th>
        <th>Actions</th>
    </tr>

    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['provider_id'] ?></td>
            <td><?= $row['provider_name'] ?></td>
            <td><?= $row['contact'] ?></td>
            <td>
                <a class="action" href="edit.php?id=<?= $row['provider_id'] ?>">Edit</a> |
                <a class="action" href="delete.php?id=<?= $row['provider_id'] ?>" onclick="return confirm('Delete this provider?')">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

