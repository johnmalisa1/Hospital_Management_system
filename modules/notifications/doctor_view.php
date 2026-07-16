<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../templates/header.php";
require_once __DIR__ . '/../../includes/classes/Notification.php';

$notification = new Notification($db);
$user_id = $_SESSION['user_id'];
$results = $notification->getNotificationsByUserId($user_id);
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="main-content">
    <a href="../../doctor_dashboard.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Dashboard</a>
    <h2 style="text-align:center;"><i class="fas fa-bell"></i> Doctor Notifications</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Message</th>
                    <th>Read?</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
            <?php
            while ($row = $results->fetch_assoc()):
            ?>
            <tr>
                <td><?= htmlspecialchars($row['message']) ?></td>
                <td>
                    <?php if ($row['is_read']): ?>
                        <span class="badge badge-completed">Yes</span>
                    <?php else: ?>
                        <span class="badge badge-pending">No</span>
                    <?php endif; ?>
                </td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
            </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include "../../templates/footer.php"; ?>
</body>
</html>
