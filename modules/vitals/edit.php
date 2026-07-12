<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM vitals WHERE vital_id = $id")->fetch_assoc();
$patients = $conn->query("SELECT * FROM patients");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $blood_pressure = $_POST['blood_pressure'];
    $pulse = $_POST['pulse'];
    $temperature = $_POST['temperature'];
    $weight = $_POST['weight'];
    $date_recorded = $_POST['date_recorded'];

    $stmt = $conn->prepare("UPDATE vitals SET patient_id=?, blood_pressure=?, pulse=?, temperature=?, weight=?, date_recorded=? WHERE vital_id=?");
    $stmt->bind_param("isiddsi", $patient_id, $blood_pressure, $pulse, $temperature, $weight, $date_recorded, $id);

    if ($stmt->execute()) {
        header("Location: view.php");
        exit();
    } else {
        $error = "Update failed.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Vitals</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="main-content">
    <h2>✏️ Edit Vitals</h2>

    <form method="POST">
        <label>Patient:</label>
        <select name="patient_id" required>
            <?php while ($p = $patients->fetch_assoc()): ?>
                <option value="<?= $p['patient_id'] ?>" <?= $data['patient_id'] == $p['patient_id'] ? 'selected' : '' ?>>
                    <?= $p['name'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Blood Pressure:</label>
        <input type="text" name="blood_pressure" value="<?= $data['blood_pressure'] ?>" required>

        <label>Pulse:</label>
        <input type="number" name="pulse" value="<?= $data['pulse'] ?>" required>

        <label>Temperature:</label>
        <input type="number" name="temperature" step="0.1" value="<?= $data['temperature'] ?>" required>

        <label>Weight:</label>
        <input type="number" name="weight" step="0.1" value="<?= $data['weight'] ?>" required>

        <label>Date:</label>
        <input type="date" name="date_recorded" value="<?= $data['date_recorded'] ?>" required>

        <button type="submit">Update</button>
    </form>
</div>
</body>
</html>
