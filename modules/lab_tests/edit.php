<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/LabTest.php";
include "../../navbar.php";

$id = $_GET['id'];
$labTest = new LabTest($db);
$row = $labTest->getLabTestById($id);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['test_name'];
    $cost = $_POST['cost'];

    $labTest->updateLabTest($id, $name, $cost);
    header("Location: view.php");
    exit();
}
?>

<h2 style="text-align:center;">✏️ Edit Lab Test</h2>
<form method="POST" style="width:400px; margin:auto; padding:20px; background:white; border-radius:10px; box-shadow:0 0 10px #ccc;">
    <label>Test Name:</label>
    <input type="text" name="test_name" value="<?= $row['test_name'] ?>" required style="width:100%; padding:10px;"><br><br>

    <label>Cost:</label>
    <input type="number" step="0.01" name="cost" value="<?= $row['cost'] ?>" required style="width:100%; padding:10px;"><br><br>

    <button type="submit" style="padding:10px 20px; background:#007bff; color:white; border:none;">Update Test</button>
</form>
