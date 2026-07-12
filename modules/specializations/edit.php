<?php
session_start();
include "../../config/db.php";
include "../../templates/header.php";

$id = $_GET['id'];
$row = $conn->query("SELECT * FROM specializations WHERE specialization_id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $stmt = $conn->prepare("UPDATE specializations SET name=? WHERE specialization_id=?");
    $stmt->bind_param("si", $name, $id);
    $stmt->execute();
    header("Location: view.php");
    exit();
}
?>

<div class="main-content">
    <h2 class="page-title">✏️ Edit Specialization</h2>
    <div class="form-container">
        <form method="POST">
            <label>Specialization Name:</label>
            <input type="text" name="name" value="<?= $row['name'] ?>" required>
            <button type="submit">Update</button>
        </form>
    </div>
</div>
