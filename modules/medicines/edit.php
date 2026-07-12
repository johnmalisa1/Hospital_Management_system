<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../../login.php"); exit(); }
include "../../config/db.php";
require_once "../../includes/classes/Medicine.php";

$id = $_GET['id'];
$medicine = new Medicine($db);
$row = $medicine->getMedicineById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $qty = $_POST['quantity'];
    $price = $_POST['price'];

    $medicine->updateMedicine($id, $name, $desc, $qty, $price);
    header("Location: view.php");
}
?>

<h2 style="text-align:center;">Edit Medicine</h2>
<form method="POST" style="width:400px;margin:auto;padding:30px;background:white;border-radius:10px;box-shadow:0 0 10px #ccc;">
    <label>Name:</label>
    <input type="text" name="name" value="<?= $row['name'] ?>" required style="width:100%;padding:10px;"><br><br>

    <label>Description:</label>
    <textarea name="description" style="width:100%;height:80px;"><?= $row['description'] ?></textarea><br><br>

    <label>Quantity:</label>
    <input type="number" name="quantity" value="<?= $row['quantity'] ?>" required style="width:100%;padding:10px;"><br><br>

    <label>Price (Tsh):</label>
    <input type="number" name="price" step="0.01" value="<?= $row['price'] ?>" required style="width:100%;padding:10px;"><br><br>

    <button type="submit" style="background:#28a745;color:white;padding:10px 20px;">Update</button>
</form>
