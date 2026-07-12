<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);

    $stmt = $conn->prepare("INSERT INTO departments (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();

    header("Location: view.php");
    exit();
}
?>

<h2 style="text-align:center;">Add Department</h2>
<form method="POST" style="width: 400px; margin: auto; background: white; padding: 30px; border-radius: 10px;">
    <label>Department Name:</label>
    <input type="text" name="name" required style="width: 100%; padding: 10px; margin-top: 10px;"><br><br>
    <button type="submit" style="padding: 10px 15px; background: #007bff; color: white;">Save</button>
</form>
