<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

include "../../config/db.php";

$id = $_GET['id'];
$data = $conn->query("SELECT * FROM discharges WHERE discharge_id = $id")->fetch_assoc();
$admissions = $conn->query("SELECT a.admission_id, p.name AS patient_name FROM admissions a JOIN patients p ON a.patient_id = p.patient_id");
$doctors = $conn->query("SELECT doctor_id, doctor_name FROM doctors");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $admission_id = $_POST['admission_id'];
    $doctor_id = $_POST['doctor_id'];
    $discharge_date = $_POST['discharge_date'];
    $summary = $_POST['summary'];
    $notes = $_POST['notes'];

    $stmt = $conn->prepare("UPDATE discharges SET admission_id=?, doctor_id=?, discharge_date=?, summary=?, notes=? WHERE discharge_id=?");
    $stmt->bind_param("iisssi", $admission_id, $doctor_id, $discharge_date, $summary, $notes, $id);

    if ($stmt->execute()) {
        header("Location: view.php");
        exit();
    } else {
        $error = "Failed to update record.";
    }
}

include "../../templates/header.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Discharge</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="main-content">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Discharges</a>
<h2>?? Edit Discharge Record</h2>

    <?php if (isset($error)): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Patient Admission:</label>
        <select name="admission_id" required>
            <?php while ($row = $admissions->fetch_assoc()): ?>
                <option value="<?= $row['admission_id'] ?>" <?= $data['admission_id'] == $row['admission_id'] ? 'selected' : '' ?>>
                    <?= $row['patient_name'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Doctor:</label>
        <select name="doctor_id" required>
            <?php while ($doc = $doctors->fetch_assoc()): ?>
                <option value="<?= $doc['doctor_id'] ?>" <?= $data['doctor_id'] == $doc['doctor_id'] ? 'selected' : '' ?>>
                    <?= $doc['doctor_name'] ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label>Discharge Date:</label>
        <input type="date" name="discharge_date" value="<?= $data['discharge_date'] ?>" required>

        <label>Summary:</label>
        <textarea name="summary" required><?= $data['summary'] ?></textarea>

        <label>Notes:</label>
        <textarea name="notes"><?= $data['notes'] ?></textarea>

        <button type="submit">Update Discharge</button>
    </form>
</div>
</body>
</html>