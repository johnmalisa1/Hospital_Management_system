<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";
require_once __DIR__ . '/../../includes/classes/Equipment.php';

$equipment = new Equipment($db);

$id = $_GET['id'];
$row = $equipment->getEquipmentById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipment->updateEquipment(
        $id,
        $_POST['name'],
        $_POST['type'],
        $_POST['quantity'],
        $_POST['status'],
        $_POST['purchase_date']
    );

    header("Location: view.php");
    exit();
}
?>

<div class="main-content">
    <h2 class="page-title">✏️ Edit Equipment</h2>
    <div class="form-container">
        <form method="POST">
            <label>Name:</label>
            <input type="text" name="name" value="<?= $row['name'] ?>" required>

            <label>Type:</label>
            <input type="text" name="type" value="<?= $row['type'] ?>" required>

            <label>Quantity:</label>
            <input type="number" name="quantity" value="<?= $row['quantity'] ?>" required>

            <label>Status:</label>
            <select name="status">
                <option value="Operational" <?= $row['status'] === 'Operational' ? 'selected' : '' ?>>Operational</option>
                <option value="Under Maintenance" <?= $row['status'] === 'Under Maintenance' ? 'selected' : '' ?>>Under Maintenance</option>
                <option value="Out of Service" <?= $row['status'] === 'Out of Service' ? 'selected' : '' ?>>Out of Service</option>
            </select>

            <label>Purchase Date:</label>
            <input type="date" name="purchase_date" value="<?= $row['purchase_date'] ?>" required>

            <button type="submit">Update</button>
        </form>
    </div>
</div>
