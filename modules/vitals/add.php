<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
$patients = $conn->query("SELECT * FROM patients");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $blood_pressure = $_POST['blood_pressure'];
    $pulse = $_POST['pulse'];
    $temperature = $_POST['temperature'];
    $weight = $_POST['weight'];
    $date_recorded = $_POST['date_recorded'];
    $recorded_by = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO vitals (patient_id, blood_pressure, pulse, temperature, weight, date_recorded, recorded_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isiddsi", $patient_id, $blood_pressure, $pulse, $temperature, $weight, $date_recorded, $recorded_by);

    if ($stmt->execute()) {
        header("Location: view.php");
        exit();
    } else {
        $error = "Failed to add vitals.";
    }
}

include "../../templates/header.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Vitals</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="main-content">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Vitals</a>
<h2>? Add Patient Vitals</h2>

    <form method="POST">
        <label>Patient:</label>
        <select name="patient_id" required>
            <option value="">-- Select Patient --</option>
            <?php while ($p = $patients->fetch_assoc()): ?>
                <option value="<?= $p['patient_id'] ?>"><?= $p['name'] ?></option>
            <?php endwhile; ?>
        </select>

        <label>Blood Pressure (e.g. 120/80):</label>
        <input type="text" name="blood_pressure" required>

        <label>Pulse (bpm):</label>
        <input type="number" name="pulse" required>

        <label>Temperature (�C):</label>
        <input type="number" step="0.1" name="temperature" required>

        <label>Weight (kg):</label>
        <input type="number" step="0.1" name="weight" required>

        <label>Date:</label>
        <input type="date" name="date_recorded" required>

        <button type="submit">Save</button>
    </form>
</div>
</body>
</html>