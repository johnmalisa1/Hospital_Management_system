<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once __DIR__ . '/../../includes/classes/BloodBank.php';
$bloodBank = new BloodBank($db);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bloodBank->addUnit(
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

include "../../templates/header.php";
?>


<div class="main-content">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to bloodbank</a>
    <h2 class="page-title">? Add Blood Unit</h2>
    <div class="form-container">
        <form method="POST">
            <label>Blood Type:</label>
            <input type="text" name="blood_type" required>

            <label>Quantity (ml):</label>
            <input type="number" name="quantity" required>

            <label>Donor Name:</label>
            <input type="text" name="donor_name">

            <label>Date Donated:</label>
            <input type="date" name="date_donated" required>

            <label>Expiry Date:</label>
            <input type="date" name="expiry_date" required>

            <label>Status:</label>
            <select name="status">
                <option value="Available">Available</option>
                <option value="Issued">Issued</option>
                <option value="Expired">Expired</option>
            </select>

            <button type="submit">Save</button>
        </form>
    </div>