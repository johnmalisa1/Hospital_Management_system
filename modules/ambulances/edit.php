<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";
require_once __DIR__ . '/../../includes/classes/Ambulance.php';

$ambulance = new Ambulance($db);

$id = $_GET['id'];
$row = $ambulance->getAmbulanceById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ambulance->updateAmbulance(
        $id,
        $_POST['vehicle_number'],
        $_POST['driver_name'],
        $_POST['contact_number'],
        $_POST['availability']
    );

    header("Location: view.php");
    exit();
}
?>

<div class="main-content">
    <h2 class="page-title">✏️ Edit Ambulance</h2>
    <div class="form-container">
        <form method="POST">
            <label>Vehicle Number:</label>
            <input type="text" name="vehicle_number" value="<?= $row['vehicle_number'] ?>" required>

            <label>Driver Name:</label>
            <input type="text" name="driver_name" value="<?= $row['driver_name'] ?>" required>

            <label>Contact Number:</label>
            <input type="text" name="contact_number" value="<?= $row['contact_number'] ?>">

            <label>Availability:</label>
            <select name="availability">
                <option value="Available" <?= $row['availability'] === 'Available' ? 'selected' : '' ?>>Available</option>
                <option value="In Use" <?= $row['availability'] === 'In Use' ? 'selected' : '' ?>>In Use</option>
                <option value="Under Maintenance" <?= $row['availability'] === 'Under Maintenance' ? 'selected' : '' ?>>Under Maintenance</option>
            </select>

            <button type="submit">Update</button>
        </form>
    </div>
</div>
