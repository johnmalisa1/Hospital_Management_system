<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blood_type = $_POST['blood_type'];
    $quantity = $_POST['quantity'];
    $donor_name = $_POST['donor_name'];
    $date_donated = $_POST['date_donated'];
    $expiry_date = $_POST['expiry_date'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO blood_bank (blood_type, quantity, donor_name, date_donated, expiry_date, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sissss", $blood_type, $quantity, $donor_name, $date_donated, $expiry_date, $status);
    $stmt->execute();

    header("Location: view.php");
    exit();
}
?>

<div class="main-content">
    <h2 class="page-title">➕ Add Blood Unit</h2>
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
</div>
