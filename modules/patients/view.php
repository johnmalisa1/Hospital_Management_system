<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}

include "../../config/db.php";
require_once "../../includes/classes/Patient.php";
include "../../templates/header.php";

$patient = new Patient($db);
?>


    <h2 class="center-text">🧾 All Registered Patients</h2>

    <div style="text-align: center; margin-bottom: 20px;">
        <a class="quick-btn" href="add.php">➕ Add Patient</a>
    </div>

    <div class="table-responsive">
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $result = $patient->getAllPatients();
            if ($result && $result->num_rows > 0):
                while ($row = $result->fetch_assoc()):
            ?>
            <tr>
                <td><?= $row['patient_id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= $row['gender'] ?></td>
                <td><?= $row['dob'] ?></td>
                <td><?= $row['phone'] ?></td>
                <td><?= htmlspecialchars($row['address']) ?></td>
                <td class="action-buttons">
    <a class="btn edit-btn" href="edit.php?id=<?= $row['patient_id'] ?>">✏️ Edit</a>
    <a class="btn delete-btn" href="delete.php?id=<?= $row['patient_id'] ?>" onclick="return confirm('Are you sure?')">🗑️ Delete</a>
</td>

</td>

                
            </tr>
            <?php endwhile; else: ?>
            <tr><td colspan="7">No patients found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>

<?php include "../../templates/footer.php"; ?>

