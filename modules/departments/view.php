<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../navbar.php";
require_once __DIR__ . '/../../includes/classes/Department.php';

$department = new Department($db);
$res = $department->getAllDepartments();
?>

<h2 style="text-align:center;">Hospital Departments</h2>

<style>
    table {
        width: 80%;
        margin: 30px auto;
        border-collapse: collapse;
        background: white;
        box-shadow: 0 0 10px #ccc;
    }
    th, td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: center;
    }
    th {
        background: #007bff;
        color: white;
    }
    a {
        text-decoration: none;
        padding: 6px 10px;
        border-radius: 5px;
        color: white;
    }
    .edit { background: #28a745; }
    .delete { background: #dc3545; }
    .add-btn {
        background: #007bff;
        padding: 10px 15px;
        display: inline-block;
        margin: 20px;
    }
</style>

<div style="text-align: center;">
    <a href="add.php" class="add-btn">+ Add Department</a>
</div>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Department Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        while ($row = $res->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['department_id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td>
                <a href="edit.php?id=<?= $row['department_id'] ?>" class="edit">Edit</a>
                <a href="delete.php?id=<?= $row['department_id'] ?>" class="delete" onclick="return confirm('Delete this department?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
