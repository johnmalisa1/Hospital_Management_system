<?php
session_start();
if (!isset($_SESSION['user_id'])) { header("Location: ../../login.php"); exit(); }
include "../../config/db.php";
include "../../templates/header.php";
require_once __DIR__ . '/../../includes/classes/Notification.php';

$notification = new Notification($db);
$res = $notification->getAllNotifications();
?>

<h2 style="text-align:center;">User Notifications</h2>

<div class="table-responsive"><table style="width:90%; margin:auto; background:white; border-collapse:collapse; box-shadow:0 0 10px #ccc;">
    <tr style="background:#007bff; color:white;">
        <th>ID</th><th>User</th><th>Message</th><th>Read?</th><th>Created</th><th>Action</th>
    </tr>
    <?php
        while ($row = $res->fetch_assoc()):
    ?>
    <tr>
        <td><?= $row['id'] ?></td>
        <td><?= $row['username'] ?></td>
        <td><?= $row['message'] ?></td>
        <td><?= $row['is_read'] ? 'Yes' : 'No' ?></td>
        <td><?= $row['created_at'] ?></td>
        <td><a href="delete.php?id=<?= $row['id'] ?>" style="color:red;" onclick="return confirm('Delete?')">Delete</a></td>
    </tr>
    <?php endwhile; ?>
</table></div>
</div>


