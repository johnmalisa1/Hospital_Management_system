<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
$id = intval($_GET['id']);
$data = $conn->query("SELECT * FROM schedules WHERE schedule_id = $id")->fetch_assoc();
$doctors = $conn->query("SELECT doctor_id, doctor_name FROM doctors");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id = $_POST['doctor_id'];
    $day = $_POST['day'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $location = $_POST['location'];

    $stmt = $conn->prepare("UPDATE schedules SET doctor_id=?, day=?, start_time=?, end_time=?, location=? WHERE schedule_id=?");
    $stmt->bind_param("issssi", $doctor_id, $day, $start_time, $end_time, $location, $id);

    if ($stmt->execute()) {
        header("Location: view.php");
        exit();
    } else {
        $error = "Update failed.";
    }
}

include "../../templates/header.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Schedule</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="main-content">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Schedules</a>
<h2>?? Edit Doctor Schedule</h2>

    <form method="POST">
        <label>Doctor:</label>
        <select name="doctor_id" required>
            <?php while ($doc = $doctors->fetch_assoc()): ?>
                <option value="<?= $doc['doctor_id'] ?>" <?= $data['doctor_id'] == $doc['doctor_id'] ? 'selected' : '' ?>>
                    <?= $doc['doctor_name'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Day:</label>
        <select name="day" required>
            <?php foreach (['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'] as $day): ?>
                <option value="<?= $day ?>" <?= $data['day'] == $day ? 'selected' : '' ?>><?= $day ?></option>
            <?php endforeach; ?>
        </select>

        <label>Start Time:</label>
        <input type="time" name="start_time" value="<?= $data['start_time'] ?>" required>

        <label>End Time:</label>
        <input type="time" name="end_time" value="<?= $data['end_time'] ?>" required>

        <label>Location:</label>
        <input type="text" name="location" value="<?= $data['location'] ?>">

        <button type="submit">Update</button>
    </form>
</div>
</body>
</html>