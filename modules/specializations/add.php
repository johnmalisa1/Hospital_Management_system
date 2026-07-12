<?php
session_start();
include "../../config/db.php";
include "../../templates/header.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $stmt = $conn->prepare("INSERT INTO specializations (name) VALUES (?)");
    $stmt->bind_param("s", $name);
    $stmt->execute();
    header("Location: view.php");
    exit();
}
?>

<div class="main-content">
    <h2 class="page-title">➕ Add Specialization</h2>
    <div class="form-container">
        <form method="POST">
            <label>Specialization Name:</label>
            <input type="text" name="name" required>
            <button type="submit">Save</button>
        </form>
    </div>
</div>
