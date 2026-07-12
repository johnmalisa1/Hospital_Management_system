<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";

$patients = $conn->query("SELECT * FROM patients");
$tests = $conn->query("SELECT * FROM lab_tests");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $lab_test_id = $_POST['lab_test_id'];
    $sample_type = $_POST['sample_type'];
    $collected_date = $_POST['collected_date'];
    $collected_by = $_SESSION['user_id'];

    $stmt = $conn->prepare("INSERT INTO test_samples (patient_id, lab_test_id, sample_type, collected_date, collected_by) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iissi", $patient_id, $lab_test_id, $sample_type, $collected_date, $collected_by);

    if ($stmt->execute()) {
        header("Location: view.php");
        exit();
    } else {
        $error = "Failed to add sample.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Test Sample</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="main-content">
    <h2>➕ Add Sample</h2>

    <form method="POST">
        <label>Patient:</label>
        <select name="patient_id" required>
            <option value="">-- Select Patient --</option>
            <?php while ($p = $patients->fetch_assoc()): ?>
                <option value="<?= $p['patient_id'] ?>"><?= $p['name'] ?></option>
            <?php endwhile; ?>
        </select>

        <label>Lab Test:</label>
        <select name="lab_test_id" required>
            <option value="">-- Select Test --</option>
            <?php while ($t = $tests->fetch_assoc()): ?>
                <option value="<?= $t['test_id'] ?>"><?= $t['test_name'] ?></option>
            <?php endwhile; ?>
        </select>

        <label>Sample Type:</label>
        <input type="text" name="sample_type" required>

        <label>Collection Date:</label>
        <input type="date" name="collected_date" required>

        <button type="submit">Save</button>
    </form>
</div>
</body>
</html>
