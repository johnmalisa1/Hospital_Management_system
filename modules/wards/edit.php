<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once __DIR__ . '/../../includes/classes/Ward.php';

$ward = new Ward($db);

$id = $_GET['id'];
$row = $ward->getWardById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ward->updateWard($id, $_POST['ward_name'], $_POST['description']);
    header("Location: view.php");
}
?>

<div class="form-container">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Wards</a>
    <h2>✏️ Edit Ward</h2>
    <form method="POST">
        <label>Ward Name</label>
        <input type="text" name="ward_name" value="<?= $row['ward_name'] ?>" required>

        <label>Description</label>
        <textarea name="description"><?= $row['description'] ?></textarea>

        <button type="submit">Update Ward</button>
    </form>
</div>
