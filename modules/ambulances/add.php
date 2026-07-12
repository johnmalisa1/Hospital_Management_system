<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $vehicle_number = $_POST['vehicle_number'];
    $driver_name = $_POST['driver_name'];
    $contact_number = $_POST['contact_number'];
    $availability = $_POST['availability'];

    $stmt = $conn->prepare("INSERT INTO ambulances (vehicle_number, driver_name, contact_number, availability) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $vehicle_number, $driver_name, $contact_number, $availability);
    $stmt->execute();

    header("Location: view.php");
    exit();
}
?>

<div class="main-content">
    <h2 class="page-title">➕ Add Ambulance</h2>
    <div class="form-container">
        <form method="POST">
            <label>Vehicle Number:</label>
            <input type="text" name="vehicle_number" required>

            <label>Driver Name:</label>
            <input type="text" name="driver_name" required>

            <label>Contact Number:</label>
            <input type="text" name="contact_number">

            <label>Availability:</label>
            <select name="availability">
                <option value="Available">Available</option>
                <option value="In Use">In Use</option>
                <option value="Under Maintenance">Under Maintenance</option>
            </select>

            <button type="submit">Save</button>
        </form>
    </div>
</div>
