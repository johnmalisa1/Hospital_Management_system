<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";

$id = $_GET['id'];
$result = $conn->query("SELECT * FROM insurance_providers WHERE provider_id = $id");
$row = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $conn->prepare("UPDATE insurance_providers SET provider_name = ?, contact = ? WHERE provider_id = ?");
    $stmt->bind_param("ssi", $_POST['provider_name'], $_POST['contact'], $id);
    $stmt->execute();
    header("Location: view.php");
}
?>

<style>
    body {
        background: #f4f6f9;
        font-family: Arial, sans-serif;
    }
    .form-container {
        width: 500px;
        margin: 50px auto;
        background: white;
        padding: 30px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    h2 {
        text-align: center;
        margin-bottom: 25px;
    }
    label {
        display: block;
        margin-bottom: 6px;
        font-weight: bold;
    }
    input {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }
    button {
        background: #007bff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 4px;
        width: 100%;
        font-size: 16px;
    }
</style>

<div class="form-container">
    <h2>✏️ Edit Insurance Provider</h2>
    <form method="POST">
        <label>Provider Name</label>
        <input type="text" name="provider_name" value="<?= $row['provider_name'] ?>" required>

        <label>Contact Info</label>
        <input type="text" name="contact" value="<?= $row['contact'] ?>" required>

        <button type="submit">Update Provider</button>
    </form>
</div>
