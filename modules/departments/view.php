<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";
require_once __DIR__ . '/../../includes/classes/Department.php';

$department = new Department($db);
$res = $department->getAllDepartments();
?>

<h2 style="text-align:center;">Hospital Departments</h2>

<div style="text-align: center;">
    <a href="add.php" class="add-btn">+ Add Department</a>
</div>

<div class="table-responsive">
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
</div>
