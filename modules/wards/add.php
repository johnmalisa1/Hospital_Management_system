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
    input, textarea {
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
    <h2>➕ Add Ward</h2>
    <form method="POST">
        <label>Ward Name</label>
        <input type="text" name="ward_name" required>

        <label>Description</label>
        <textarea name="description"></textarea>

        <button type="submit">Save Ward</button>
    </form>
</div>
