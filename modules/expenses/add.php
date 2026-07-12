<?php
session_start();
include "../../config/db.php";
include "../../templates/header.php";
require_once __DIR__ . '/../../includes/classes/Expense.php';

$expense = new Expense($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expense->addExpense(
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
    <h2 class="page-title">➕ Add Expense</h2>
    <div class="form-container">
        <form method="POST">
            <label>Category:</label>
            <input type="text" name="category" required>

            <label>Amount (Tsh):</label>
            <input type="number" step="0.01" name="amount" required>

            <label>Date:</label>
            <input type="date" name="expense_date" required>

            <label>Notes:</label>
            <input type="text" name="notes">

            <button type="submit">Save</button>
        </form>
    </div>
</div>
