<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";
require_once __DIR__ . '/../../includes/classes/BloodBank.php';

$bloodBank = new BloodBank($db);

$id = $_GET['id'];
$row = $bloodBank->getUnitById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bloodBank->updateUnit(
        $id,
        $_POST['blood_type'],
        $_POST['quantity'],
        $_POST['donor_name'],
        $_POST['date_donated'],
        $_POST['expiry_date'],
        $_POST['status']
    );

    header("Location: view.php");
    exit();
}
?>


<div class="main-content">
    <h2 class="page-title">✏️ Edit Blood Unit</h2>
    <div class="form-container">
        <form method="POST">
            <label>Blood Type:</label>
            <input type="text" name="blood_type" value="<?= $row['blood_type'] ?>" required>

            <label>Quantity (ml):</label>
            <input type="number" name="quantity" value="<?= $row['quantity'] ?>" required>

            <label>Donor Name:</label>
            <input type="text" name="donor_name" value="<?= $row['donor_name'] ?>">

            <label>Date Donated:</label>
            <input type="date" name="date_donated" value="<?= $row['date_donated'] ?>" required>

            <label>Expiry Date:</label>
            <input type="date" name="expiry_date" value="<?= $row['expiry_date'] ?>" required>

            <label>Status:</label>
            <select name="status">
                <option value="Available" <?= $row['status'] === 'Available' ? 'selected' : '' ?>>Available</option>
                <option value="Issued" <?= $row['status'] === 'Issued' ? 'selected' : '' ?>>Issued</option>
                <option value="Expired" <?= $row['status'] === 'Expired' ? 'selected' : '' ?>>Expired</option>
            </select>

            <button type="submit">Update</button>
        </form>
    </div>
</div>
