<?php
session_start();
include "../../config/db.php";
include "../../navbar.php";

$id = $_GET['id'];
$row = $conn->query("SELECT * FROM users WHERE id = $id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $role = $_POST['role'];

    $stmt = $conn->prepare("UPDATE users SET username=?, role=? WHERE id=?");
    $stmt->bind_param("ssi", $username, $role, $id);
    $stmt->execute();
    header("Location: view.php");
    exit();
}
?>

<h2 style="text-align:center;">✏️ Edit User</h2>
<form method="POST" style="width:400px; margin:auto; padding:20px; background:white; border-radius:10px; box-shadow:0 0 10px #ccc;">
    <label>Username:</label>
    <input type="text" name="username" value="<?= $row['username'] ?>" required style="width:100%; padding:10px;"><br><br>

    <label>Role:</label>
    <select name="role" required style="width:100%; padding:10px;">
        <option value="Admin" <?= $row['role'] == 'Admin' ? 'selected' : '' ?>>Admin</option>
        <option value="Doctor" <?= $row['role'] == 'Doctor' ? 'selected' : '' ?>>Doctor</option>
        <option value="Receptionist" <?= $row['role'] == 'Receptionist' ? 'selected' : '' ?>>Receptionist</option>
    </select><br><br>

    <button type="submit" style="padding:10px 20px; background:#007bff; color:white; border:none;">Update User</button>
</form>
