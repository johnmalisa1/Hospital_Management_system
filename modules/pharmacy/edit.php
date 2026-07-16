<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/Medicine.php";
$medicine = new Medicine($db);
$id = intval($_GET['id']);
$row = $medicine->getMedicineById($id);
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $qty = $_POST['quantity'];
    $price = $_POST['price'];

    $medicine->updateMedicine((int)$id, $name, '', (int)$qty, (int)$price);
    header("Location: view.php");
    exit();
}

include "../../templates/header.php";
?>

<a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to pharmacy</a>
<h2 style="text-align:center;">?? Edit Medicine</h2>
<form method="POST" style="width:400px; margin:auto; padding:20px; background:white; border-radius:10px; box-shadow:0 0 10px #ccc;">
    <label>Medicine Name:</label>
    <input type="text" name="name" value="<?= $row['name'] ?>" required style="width:100%; padding:10px;"><br><br>

    <label>Quantity:</label>
    <input type="number" name="quantity" value="<?= $row['quantity'] ?>" required style="width:100%; padding:10px;"><br><br>

    <label>Price:</label>
    <input type="number" step="0.01" name="price" value="<?= $row['price'] ?>" required style="width:100%; padding:10px;"><br><br>

    <button type="submit" style="padding:10px 20px; background:#007bff; color:white; border:none;">Update</button>
</form>
</div>