<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";

$id = $_GET['id'];
$res = $conn->prepare("SELECT * FROM departments WHERE department_id = ?");
$res->bind_param("i", $id);
$res->execute();
$dept = $res->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $stmt = $conn->prepare("UPDATE departments SET name = ? WHERE department_id = ?");
    $stmt->bind_param("si", $name, $id);
    $stmt->execute();
    header("Location: view.php");
    exit();
}
?>

<h2 style="text-align:center;">Edit Department</h2>
<form method="POST" style="width: 400px; margin: auto; background: white; padding: 30px; border-radius: 10px;">
    <label>Department Name:</label>
    <input type="text" name="name" value="<?= htmlspecialchars($dept['name']) ?>" required style="width: 100%; padding: 10px;"><br><br>
    <button type="submit" style="padding: 10px 15px; background: #28a745; color: white;">Update</button>
</form>
