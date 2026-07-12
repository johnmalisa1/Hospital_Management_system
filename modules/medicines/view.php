<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Medicine.php";
include "../../navbar.php";

$medicine = new Medicine($db);
?>

<h2 style="text-align:center;">Medicines Inventory</h2>

<div style="text-align:center; margin: 20px;">
    <a href="add.php" style="background:#007bff;color:white;padding:10px 20px;border-radius:5px;text-decoration:none;">+ Add Medicine</a>
</div>

<table style="width:90%; margin:auto; background:white; border-collapse:collapse; box-shadow: 0 0 10px #ccc;">
    <tr style="background:#007bff; color:white;">
        <th>ID</th><th>Name</th><th>Description</th><th>Quantity</th><th>Price (Tsh)</th><th>Actions</th>
    </tr>
    <?php
    $res = $medicine->getAllMedicines();
    while ($m = $res->fetch_assoc()):
    ?>
    <tr>
        <td><?= $m['medicine_id'] ?></td>
        <td><?= $m['name'] ?></td>
        <td><?= $m['description'] ?></td>
        <td><?= $m['quantity'] ?></td>
        <td><?= $m['price'] ?></td>
        <td>
            <a href="edit.php?id=<?= $m['medicine_id'] ?>" style="color:green;">Edit</a> |
            <a href="delete.php?id=<?= $m['medicine_id'] ?>" style="color:red;" onclick="return confirm('Delete?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
