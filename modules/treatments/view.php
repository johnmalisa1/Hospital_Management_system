<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/Treatment.php";
include "../../templates/header.php";

$treatment = new Treatment($db);
$result = $treatment->getAllTreatments();
?>

    <h2 style="text-align:center;">📝 Treatments</h2>
    <div style="text-align: center; margin-bottom: 20px;">
        <a href="add.php" class="quick-btn">➕ Add Treatment</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['treatment_id'] ?></td>
                    <td><?= htmlspecialchars($row['patient_name']) ?></td>
                    <td><?= htmlspecialchars($row['doctor_name']) ?></td>
                    <td><?= htmlspecialchars($row['date_given']) ?></td>
                    <td><?= htmlspecialchars($row['description']) ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="edit.php?id=<?= $row['treatment_id'] ?>" class="btn edit-btn"><i class="fas fa-edit"></i> Edit</a>
                            <a href="delete.php?id=<?= $row['treatment_id'] ?>" onclick="return confirm('Delete this treatment?')" class="btn delete-btn"><i class="fas fa-trash"></i> Delete</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../../templates/footer.php"; ?>
