<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
$id = intval($_GET['id']);
$conn->query("DELETE FROM users WHERE id = $id");
header("Location: view.php");
exit();
?>
