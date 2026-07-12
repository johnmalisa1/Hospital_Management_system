<?php
session_start();
include "../../config/db.php";
require_once "../../includes/classes/LabTest.php";
include "../../navbar.php";

$labTest = new LabTest($db);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['test_name'];
    $cost = $_POST['cost'];

    $labTest->addLabTest($name, $cost);
    header("Location: view.php");
    exit();
}
?>

<h2 style="text-align:center;">➕ Add Lab Test</h2>
<form method="POST" style="width:400px; margin:auto; padding:20px; background:white; border-radius:10px; box-shadow:0 0 10px #ccc;">
    <label>Test Name:</label>
    <input type="text" name="test_name" required style="width:100%; padding:10px;"><br><br>

    <label>Cost:</label>
    <input type="number" step="0.01" name="cost" required style="width:100%; padding:10px;"><br><br>

    <button type="submit" style="padding:10px 20px; background:#28a745; color:white; border:none;">Save Test</button>
</form>
