<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../navbar.php";

$result = $conn->query("SELECT * FROM wards ORDER BY ward_id DESC");
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
        width: 200px;
        margin: 20px auto 10px auto;
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
        background-color: #007bff;
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

<h2>🏥 All Wards</h2>
<a class="add-btn" href="add.php">+ Add Ward</a>

<table>
    <tr>
        <th>ID</th>
        <th>Ward Name</th>
        <th>Description</th>
        <th>Actions</th>
    </tr>

    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['ward_id'] ?></td>
            <td><?= $row['ward_name'] ?></td>
            <td><?= $row['description'] ?></td>
            <td>
                <a class="action" href="edit.php?id=<?= $row['ward_id'] ?>">Edit</a> |
                <a class="action" href="delete.php?id=<?= $row['ward_id'] ?>" onclick="return confirm('Delete this ward?')">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
