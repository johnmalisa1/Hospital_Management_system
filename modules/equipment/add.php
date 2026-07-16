<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once __DIR__ . '/../../includes/classes/Equipment.php';
$equipment = new Equipment($db);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $equipment->addEquipment(
        $_POST['name'],
        $_POST['type'],
        $_POST['quantity'],
        $_POST['status'],
        $_POST['purchase_date']
    );

    header("Location: view.php");
    exit();
}

include "../../templates/header.php";
?>

<div class="main-content">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to equipment</a>
    <h2 class="page-title">? Add Equipment</h2>
    <div class="form-container">
        <form method="POST">
            <label>Name:</label>
            <input type="text" name="name" required>

            <label>Type:</label>
            <input type="text" name="type" required>

            <label>Quantity:</label>
            <input type="number" name="quantity" required>

            <label>Status:</label>
            <select name="status">
                <option value="Operational">Operational</option>
                <option value="Under Maintenance">Under Maintenance</option>
                <option value="Out of Service">Out of Service</option>
            </select>

            <label>Purchase Date:</label>
            <input type="date" name="purchase_date" required>

            <button type="submit">Save</button>
        </form>
    </div>