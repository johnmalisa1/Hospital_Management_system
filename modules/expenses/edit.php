<?php
session_start();
include "../../config/db.php";
include "../../templates/header.php";
require_once __DIR__ . '/../../includes/classes/Expense.php';

$expense = new Expense($db);

$id = $_GET['id'];
$row = $expense->getExpenseById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expense->updateExpense(
        $id,
        $_POST['category'],
        $_POST['amount'],
        $_POST['expense_date'],
        $_POST['notes']
    );
    header("Location: view.php");
    exit();
}
?>

<div class="main-content">
    <h2 class="page-title">✏️ Edit Expense</h2>
    <div class="form-container">
        <form method="POST">
            <label>Category:</label>
            <input type="text" name="category" value="<?= $row['category'] ?>" required>

            <label>Amount (Tsh):</label>
            <input type="number" name="amount" step="0.01" value="<?= $row['amount'] ?>" required>

            <label>Date:</label>
            <input type="date" name="expense_date" value="<?= $row['expense_date'] ?>" required>

            <label>Notes:</label>
            <input type="text" name="notes" value="<?= $row['notes'] ?>">

            <button type="submit">Update</button>
        </form>
    </div>
</div>
