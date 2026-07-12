<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Patient') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
include "../../navbar.php";

$user_id = $_SESSION['user_id'];
?>

<h2 style="text-align:center;">My Notifications</h2>

<table style="width: 80%; margin: auto; background: white; border-collapse: collapse; box-shadow: 0 0 10px #ccc;">
    <tr style="background: #007bff; color: white;">
        <th>Message</th>
        <th>Read?</th>
        <th>Created At</th>
    </tr>
    <?php
    $sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $results = $stmt->get_result();

    while ($row = $results->fetch_assoc()):
    ?>
    <tr>
        <td><?= $row['message'] ?></td>
        <td><?= $row['is_read'] ? 'Yes' : 'No' ?></td>
        <td><?= $row['created_at'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>
