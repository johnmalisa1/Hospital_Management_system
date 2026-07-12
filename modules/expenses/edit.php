<?php
session_start();
include "../../config/db.php";
include "../../templates/header.php";

$id = $_GET['id'];
$row = $conn->query("SELECT * FROM expenses WHERE expense_id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category = $_POST['category'];
    $amount = $_POST['amount'];
    $expense_date = $_POST['expense_date'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("UPDATE expenses SET category=?, amount=?, expense_date=?, notes=? WHERE expense_id=?");
    $stmt->bind_param("sdssi", $category, $amount, $expense_date, $notes, $id);
    $stmt->execute();
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
