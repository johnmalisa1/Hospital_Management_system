<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";

$result = $conn->query("
    SELECT v.*, p.name AS patient_name, u.username AS recorded_by_name
    FROM vitals v
    JOIN patients p ON v.patient_id = p.patient_id
    LEFT JOIN users u ON v.recorded_by = u.id
    ORDER BY v.date_recorded DESC
");
?>


    <h2 class="page-title">❤️ Patient Vitals</h2>
    <div style="text-align: center; margin-bottom: 20px;">
    <a href="add.php" class="quick-btn">➕ Add Vitals</a>
</div>
    <div class="table-responsive"><table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>BP</th>
                <th>Pulse</th>
                <th>Temp</th>
                <th>Weight</th>
                <th>Date</th>
                <th>Recorded By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['vital_id'] ?></td>
                <td><?= $row['patient_name'] ?></td>
                <td><?= $row['blood_pressure'] ?></td>
                <td><?= $row['pulse'] ?></td>
                <td><?= $row['temperature'] ?>°C</td>
                <td><?= $row['weight'] ?>kg</td>
                <td><?= $row['date_recorded'] ?></td>
                <td><?= $row['recorded_by_name'] ?? 'N/A' ?></td>
                <td class="action-buttons">
                    <a href="edit.php?id=<?= $row['vital_id'] ?>" class="btn edit-btn">Edit</a>
                    <a href="delete.php?id=<?= $row['vital_id'] ?>" class="btn delete-btn" onclick="return confirm('Delete this record?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table></div>
</div>
<?php include "../../templates/footer.php"; ?>


