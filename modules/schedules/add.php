<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";

$doctors = $conn->query("SELECT doctor_id, doctor_name FROM doctors");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = $_POST['doctor_id'];
    $day = $_POST['day'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $location = $_POST['location'];

    $stmt = $conn->prepare("INSERT INTO schedules (doctor_id, day, start_time, end_time, location) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("issss", $doctor_id, $day, $start_time, $end_time, $location);

    if ($stmt->execute()) {
        header("Location: view.php");
        exit();
    } else {
        $error = "Failed to add schedule.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Doctor Schedule</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="main-content">
    <h2>➕ Add Schedule</h2>

    <form method="POST">
        <label>Doctor:</label>
        <select name="doctor_id" required>
            <option value="">-- Select Doctor --</option>
            <?php while ($doc = $doctors->fetch_assoc()): ?>
                <option value="<?= $doc['doctor_id'] ?>"><?= $doc['doctor_name'] ?></option>
            <?php endwhile; ?>
        </select>

        <label>Day:</label>
        <select name="day" required>
            <option value="">-- Select Day --</option>
            <?php foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day): ?>
                <option value="<?= $day ?>"><?= $day ?></option>
            <?php endforeach; ?>
        </select>

        <label>Start Time:</label>
        <input type="time" name="start_time" required>

        <label>End Time:</label>
        <input type="time" name="end_time" required>

        <label>Location:</label>
        <input type="text" name="location">

        <button type="submit">Save</button>
    </form>
</div>
</body>
</html>
