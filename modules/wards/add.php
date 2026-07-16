<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once __DIR__ . '/../../includes/classes/Ward.php';
$ward = new Ward($db);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ward->addWard($_POST['ward_name'], $_POST['description']);
    header("Location: view.php");
}

include "../../templates/header.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>

<div class="main-content">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Wards</a>
    <h2 style="text-align:center;"><i class="fas fa-plus-circle"></i> Add Ward</h2>

    <form method="POST" class="form-container">
        <label>Ward Name</label>
        <input type="text" name="ward_name" required>

        <label>Description</label>
        <textarea name="description" rows="4" style="width: 100%; padding: 12px; border: 2px solid #E2E8F0; border-radius: 8px; font-family: inherit; font-size: 14px; resize: vertical;"></textarea>

        <button type="submit"><i class="fas fa-save"></i> Save Ward</button>
    </form>
</div>

<?php include "../../templates/footer.php"; ?>
</body>
</html>
