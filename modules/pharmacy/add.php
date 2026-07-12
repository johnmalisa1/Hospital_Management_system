<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/Pharmacy.php";
include "../../navbar.php";

$pharmacy = new Pharmacy($db);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $qty = $_POST['quantity'];
    $price = $_POST['price'];

    $pharmacy->addMedicine($name, $qty, $price);
    header("Location: view.php");
    exit();
}
?>

<h2 style="text-align:center;">➕ Add Medicine</h2>
<form method="POST" style="width:400px; margin:auto; padding:20px; background:white; border-radius:10px; box-shadow:0 0 10px #ccc;">
    <label>Medicine Name:</label>
    <input type="text" name="name" required style="width:100%; padding:10px;"><br><br>

    <label>Quantity:</label>
    <input type="number" name="quantity" required style="width:100%; padding:10px;"><br><br>

    <label>Price:</label>
    <input type="number" step="0.01" name="price" required style="width:100%; padding:10px;"><br><br>

    <button type="submit" style="padding:10px 20px; background:#28a745; color:white; border:none;">Save Medicine</button>
</form>
