<?php
session_start();
include "../../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $billing_id = $_POST['billing_id'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];

    $stmt = $conn->prepare("INSERT INTO invoice_items (billing_id, description, amount) VALUES (?, ?, ?)");
    $stmt->bind_param("isd", $billing_id, $description, $amount);
    $stmt->execute();
    header("Location: view.php");
}
?>

<h2 style="text-align:center;">Add Invoice Item</h2>
<form method="POST" style="width:400px;margin:auto;padding:30px;background:white;border-radius:10px;box-shadow:0 0 10px #ccc;">
    <label>Billing ID:</label>
    <input type="number" name="billing_id" required style="width:100%;padding:10px;"><br><br>

    <label>Description:</label>
    <input type="text" name="description" required style="width:100%;padding:10px;"><br><br>

    <label>Amount (Tsh):</label>
    <input type="number" name="amount" step="0.01" required style="width:100%;padding:10px;"><br><br>

    <button type="submit" style="background:#007bff;color:white;padding:10px 20px;">Add</button>
</form>
