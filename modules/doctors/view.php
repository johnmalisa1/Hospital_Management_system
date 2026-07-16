<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

include "../../config/db.php";
require_once "../../includes/classes/Doctor.php";
include "../../templates/header.php";

$doctor = new Doctor($db);
$result = $doctor->getAllDoctors();
?>


    <a href="../../dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    <h2 class="center-text">All Doctors</h2>

    <div style="text-align: center; margin-bottom: 20px;">
        <a href="add.php" class="quick-btn">➕ Add Doctor</a>
    </div>

    <div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Specialization</th>
                <th>Phone</th>
                <th>Department</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['doctor_id'] ?></td>
                        <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                        <td><?= htmlspecialchars($row['specialization']) ?></td>
                        <td><?= htmlspecialchars($row['phone']) ?></td>
                        <td><?= htmlspecialchars($row['department'] ?? 'N/A') ?></td>
                        <td class="action-buttons">
                            <a href="edit.php?id=<?= $row['doctor_id'] ?>" class="btn edit-btn">✏️ Edit</a>
                            <a href="delete.php?id=<?= $row['doctor_id'] ?>" class="btn delete-btn" onclick="return confirm('Are you sure?')">🗑️ Delete</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="6">No doctors found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>

<?php include "../../templates/footer.php"; ?>

