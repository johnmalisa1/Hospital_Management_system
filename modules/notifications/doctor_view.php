<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Doctor') {
    header("Location: ../../login_doctor.php");
    exit();
}
include "../../config/db.php";
require_once __DIR__ . '/../../includes/classes/Notification.php';

$notification = new Notification($db);
$user_id = $_SESSION['user_id'];
$results = $notification->getNotificationsByUserId($user_id);

include "../../templates/header.php";
?>

<!DOCTYPE html>
<html>
<head>
    <title>Doctor Notifications</title>
</head>

<body class="sidebar-page">
    <div class="main-overlay">

    <div class="page-header">
        <h2><i class="fas fa-bell"></i> Doctor Notifications</h2>
    </div>

    <?php if ($results->num_rows > 0): ?>
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
    <?php else: ?>
    <p class="no-data">No notifications found.</p>
    <?php endif; ?>

    </div>

<?php include "../../templates/footer.php"; ?>
