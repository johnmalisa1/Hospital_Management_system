<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}

include "../../config/db.php";

$item_id = $_GET['id'] ?? null;

if (!$item_id) {
    header("Location: view.php");
    exit();
}

// Fetch existing item
$stmt = $conn->prepare("SELECT * FROM invoice_items WHERE item_id = ?");
$stmt->bind_param("i", $item_id);
$stmt->execute();
$result = $stmt->get_result();
$item = $result->fetch_assoc();

if (!$item) {
    echo "Invoice item not found.";
    exit();
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $billing_id = $_POST['billing_id'];
    $description = $_POST['description'];
    $amount = $_POST['amount'];

    $update = $conn->prepare("UPDATE invoice_items SET billing_id = ?, description = ?, amount = ? WHERE item_id = ?");
    $update->bind_param("isdi", $billing_id, $description, $amount, $item_id);
    $update->execute();

    header("Location: view.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Invoice Item</title>
    <style>
        body {
            background: #f2f2f2;
            font-family: Arial;
            padding: 40px;
        }
        form {
            width: 400px;
            background: white;
            padding: 30px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }
        input {
            width: 100%;
            margin: 10px 0;
            padding: 10px;
        }
        button {
            background: #28a745;
            color: white;
            padding: 10px;
            width: 100%;
            border: none;
        }
    </style>
</head>
<body>

<h2 style="text-align:center;">Edit Invoice Item</h2>
<form method="POST" style="width:400px;margin:auto;padding:30px;background:white;border-radius:10px;box-shadow:0 0 10px #ccc;">
    <label>Billing ID:</label>
    <input type="number" name="billing_id" value="<?= $row['billing_id'] ?>" required style="width:100%;padding:10px;"><br><br>

    <label>Description:</label>
    <input type="text" name="description" value="<?= $row['description'] ?>" required style="width:100%;padding:10px;"><br><br>

    <label>Amount (Tsh):</label>
    <input type="number" name="amount" step="0.01" value="<?= $row['amount'] ?>" required style="width:100%;padding:10px;"><br><br>

    <button type="submit" style="background:#28a745;color:white;padding:10px 20px;">Update</button>
</form>
