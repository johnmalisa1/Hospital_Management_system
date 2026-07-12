<?php
session_start();
include "../../config/db.php";
include "../../navbar.php";
?>

<h2 style="text-align:center;">Invoice Items</h2>

<div style="text-align:center; margin: 20px;">
    <a href="add.php" style="background:#007bff;color:white;padding:10px 20px;border-radius:5px;text-decoration:none;">+ Add Item</a>
</div>

<table style="width: 80%; margin: auto; background: white; border-collapse: collapse; box-shadow: 0 0 10px #ccc;">
    <tr style="background: #007bff; color: white;">
        <th>ID</th><th>Billing ID</th><th>Description</th><th>Amount (Tsh)</th><th>Action</th>
    </tr>
    <?php
    $res = $conn->query("SELECT * FROM invoice_items ORDER BY item_id DESC");
    while ($row = $res->fetch_assoc()):
    ?>
    <tr>
        <td><?= $row['item_id'] ?></td>
        <td><?= $row['billing_id'] ?></td>
        <td><?= $row['description'] ?></td>
        <td><?= $row['amount'] ?></td>
        <td>
            <a href="edit.php?id=<?= $row['item_id'] ?>" style="color:green;">Edit</a> |
            <a href="delete.php?id=<?= $row['item_id'] ?>" style="color:red;" onclick="return confirm('Delete?')">Delete</a>
        </td>
    </tr>
    <?php endwhile; ?>
</table>
