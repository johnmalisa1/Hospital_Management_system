<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
$id = intval($_GET['id']);
$data = $conn->query("SELECT * FROM test_samples WHERE sample_id = $id")->fetch_assoc();
$patients = $conn->query("SELECT * FROM patients");
$tests = $conn->query("SELECT * FROM lab_tests");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $patient_id = $_POST['patient_id'];
    $lab_test_id = $_POST['lab_test_id'];
    $sample_type = $_POST['sample_type'];
    $collected_date = $_POST['collected_date'];

    $stmt = $conn->prepare("UPDATE test_samples SET patient_id=?, lab_test_id=?, sample_type=?, collected_date=? WHERE sample_id=?");
    $stmt->bind_param("iissi", $patient_id, $lab_test_id, $sample_type, $collected_date, $id);

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
    <title>Edit Sample</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="main-content">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Test Samples</a>
<h2>?? Edit Sample</h2>

    <form method="POST">
        <label>Patient:</label>
        <select name="patient_id" required>
            <?php while ($p = $patients->fetch_assoc()): ?>
                <option value="<?= $p['patient_id'] ?>" <?= $data['patient_id'] == $p['patient_id'] ? 'selected' : '' ?>>
                    <?= $p['name'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Lab Test:</label>
        <select name="lab_test_id" required>
            <?php while ($t = $tests->fetch_assoc()): ?>
                <option value="<?= $t['test_id'] ?>" <?= $data['lab_test_id'] == $t['test_id'] ? 'selected' : '' ?>>
                    <?= $t['test_name'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Sample Type:</label>
        <input type="text" name="sample_type" value="<?= $data['sample_type'] ?>" required>

        <label>Collection Date:</label>
        <input type="date" name="collected_date" value="<?= $data['collected_date'] ?>" required>

        <button type="submit">Update</button>
    </form>
</div>
</body>
</html>