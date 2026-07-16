<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";

$result = $conn->query("
    SELECT s.*, d.doctor_name 
    FROM schedules s
    JOIN doctors d ON s.doctor_id = d.doctor_id
    ORDER BY s.day, s.start_time
");
?>


    <h2 class="page-title">🗓️ Doctor Schedules</h2>
    <div style="text-align: center; margin-bottom: 20px;">
    <a href="add.php" class="quick-btn">➕ Add Schedule</a>
</div>

    <div class="table-responsive"><table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Doctor</th>
                <th>Day</th>
                <th>Start Time</th>
                <th>End Time</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['schedule_id'] ?></td>
                <td><?= $row['doctor_name'] ?></td>
                <td><?= $row['day'] ?></td>
                <td><?= $row['start_time'] ?></td>
                <td><?= $row['end_time'] ?></td>
                <td><?= $row['location'] ?></td>
                <td class="action-buttons">
                    <a href="edit.php?id=<?= $row['schedule_id'] ?>" class="btn edit-btn">Edit</a>
                    <a href="delete.php?id=<?= $row['schedule_id'] ?>" class="btn delete-btn" onclick="return confirm('Delete schedule?')">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table></div>
</div>

<?php include "../../templates/footer.php"; ?>


