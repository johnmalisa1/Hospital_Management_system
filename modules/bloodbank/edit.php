<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM blood_bank WHERE unit_id = $id");
$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blood_type = $_POST['blood_type'];
    $quantity = $_POST['quantity'];
    $donor_name = $_POST['donor_name'];
    $date_donated = $_POST['date_donated'];
    $expiry_date = $_POST['expiry_date'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("UPDATE blood_bank SET blood_type=?, quantity=?, donor_name=?, date_donated=?, expiry_date=?, status=? WHERE unit_id=?");
    $stmt->bind_param("sissssi", $blood_type, $quantity, $donor_name, $date_donated, $expiry_date, $status, $id);
    $stmt->execute();

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
