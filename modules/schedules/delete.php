<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM schedules WHERE schedule_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: view.php");
exit();
?>
