<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

include "../../config/db.php";
include "../../templates/header.php";

$result = $conn->query("
    SELECT d.*, a.patient_id, p.name AS patient_name, doc.doctor_name
    FROM discharges d
    JOIN admissions a ON d.admission_id = a.admission_id
    JOIN patients p ON a.patient_id = p.patient_id
    LEFT JOIN doctors doc ON d.doctor_id = doc.doctor_id
    ORDER BY d.discharge_date DESC
");
?>


    <h2>🏁 Patient Discharges</h2>

    <a href="add.php" class="quick-btn">➕ Add Discharge</a>

    <div class="table-responsive"><table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Patient</th>
                <th>Doctor</th>
                <th>Date</th>
                <th>Summary</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['discharge_id'] ?></td>
                    <td><?= $row['patient_name'] ?></td>
                    <td><?= $row['doctor_name'] ?? 'N/A' ?></td>
                    <td><?= $row['discharge_date'] ?></td>
                    <td><?= substr($row['summary'], 0, 50) ?>...</td>
                    <td class="action-buttons">
                        <a href="edit.php?id=<?= $row['discharge_id'] ?>" class="btn edit-btn">Edit</a>
                        <a href="delete.php?id=<?= $row['discharge_id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">No discharge records found.</td></tr>
        <?php endif; ?>
        </tbody>
    </table></div>
</div>

<?php include "../../templates/footer.php"; ?>


