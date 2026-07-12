<?php
session_start();
include "../../config/db.php";
include "../../templates/header.php";
?>

<div class="main-content">
    <h2 class="page-title">💰 Expenses</h2>
    <div class="center-btn">
        <a href="add.php" class="quick-btn">+ Add Expense</a>
    </div>

    <table>
        <tr>
            <th>ID</th>
            <th>Category</th>
            <th>Amount (Tsh)</th>
            <th>Date</th>
            <th>Notes</th>
            <th>Actions</th>
        </tr>

        <?php
        $result = $conn->query("SELECT * FROM expenses ORDER BY expense_date DESC");
        while ($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?= $row['expense_id'] ?></td>
            <td><?= $row['category'] ?></td>
            <td><?= number_format($row['amount'], 2) ?></td>
            <td><?= $row['expense_date'] ?></td>
            <td><?= $row['notes'] ?></td>
            <td class="action-buttons">
                <a href="edit.php?id=<?= $row['expense_id'] ?>" class="btn edit-btn">Edit</a>
                <a href="delete.php?id=<?= $row['expense_id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>
