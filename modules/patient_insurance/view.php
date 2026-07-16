<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";
require_once __DIR__ . '/../../includes/classes/PatientInsurance.php';

$patientInsurance = new PatientInsurance($db);
$result = $patientInsurance->getAllInsurance();
?>

    <h2 style="text-align:center;">🩺 Patient Insurance</h2>
    <div style="text-align: center; margin-bottom: 20px;">
        <a href="add.php" class="quick-btn">➕ Add Insurance Info</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Patient Name</th>
                    <th>Provider</th>
                    <th>Policy Number</th>
                    <th>Valid Until</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['patient_name']) ?></td>
                    <td><?= htmlspecialchars($row['provider_name']) ?></td>
                    <td><?= htmlspecialchars($row['policy_number']) ?></td>
                    <td><?= htmlspecialchars($row['valid_until']) ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="edit.php?id=<?= $row['id'] ?>" class="btn edit-btn"><i class="fas fa-edit"></i> Edit</a>
                            <a href="delete.php?id=<?= $row['id'] ?>" onclick="return confirm('Delete this record?')" class="btn delete-btn"><i class="fas fa-trash"></i> Delete</a>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../../templates/footer.php"; ?>
