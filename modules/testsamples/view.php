<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";

$result = $conn->query("
    SELECT ts.*, p.name AS patient_name, lt.test_name, u.username AS collected_by_name
    FROM test_samples ts
    JOIN patients p ON ts.patient_id = p.patient_id
    JOIN lab_tests lt ON ts.lab_test_id = lt.test_id
    LEFT JOIN users u ON ts.collected_by = u.id
    ORDER BY ts.collected_date DESC
");
?>


    <h2 class="page-title">🧪 Collected Test Samples</h2>
    <div style="text-align: center; margin-bottom: 20px;">
    <a href="add.php" class="quick-btn">➕ Add Sample</a>
</div>
    <div class="table-responsive"><table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Test</th>
                <th>Sample Type</th>
                <th>Date</th>
                <th>Collected By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['sample_id'] ?></td>
                <td><?= $row['patient_name'] ?></td>
                <td><?= $row['test_name'] ?></td>
                <td><?= $row['sample_type'] ?></td>
                <td><?= $row['collected_date'] ?></td>
                <td><?= $row['collected_by_name'] ?? 'N/A' ?></td>
                <td class="action-buttons">
                    <a href="edit.php?id=<?= $row['sample_id'] ?>" class="btn edit-btn">Edit</a>
                    <a href="delete.php?id=<?= $row['sample_id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table></div>
</div>
<?php include "../../templates/footer.php"; ?>


