<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

include "../../config/db.php";
require_once "../../includes/classes/MedicalHistory.php";
include "../../templates/header.php";

$medicalHistory = new MedicalHistory($db);
$result = $medicalHistory->getAllHistory();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Medical History</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>

<div class="main-content">
    <h2 class="page-title">🧾 Medical History</h2>
    <div class="center-btn">
    <a href="add.php" class="quick-btn">➕ Add History</a>
</div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Condition</th>
                <th>Treatment</th>
                <th>Date</th>
                <th>Recorded By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['history_id'] ?></td>
                <td><?= $row['patient_name'] ?></td>
                <td><?= htmlspecialchars($row['condition']) ?></td>
                <td><?= htmlspecialchars($row['treatment']) ?></td>
                <td><?= $row['date_recorded'] ?></td>
                <td><?= $row['recorded_by_name'] ?? 'N/A' ?></td>
                <td class="action-buttons">
                    <a href="edit.php?id=<?= $row['history_id'] ?>" class="btn edit-btn">Edit</a>
                    <a href="delete.php?id=<?= $row['history_id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include "../../templates/footer.php"; ?>
</body>
</html>
