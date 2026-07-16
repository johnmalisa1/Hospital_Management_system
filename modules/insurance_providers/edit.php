<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once __DIR__ . '/../../includes/classes/InsuranceProvider.php';

$insuranceProvider = new InsuranceProvider($db);
$id = $_GET['id'];
$row = $insuranceProvider->getProviderById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $insuranceProvider->updateProvider($id, $_POST['provider_name'], $_POST['contact']);
    header("Location: view.php");
}
?>

<div class="form-container">
    <a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to Insurance Providers</a>
    <h2>✏️ Edit Insurance Provider</h2>
    <form method="POST">
        <label>Provider Name</label>
        <input type="text" name="provider_name" value="<?= $row['provider_name'] ?>" required>

        <label>Contact Info</label>
        <input type="text" name="contact" value="<?= $row['contact'] ?>" required>

        <button type="submit">Update Provider</button>
    </form>
</div>
