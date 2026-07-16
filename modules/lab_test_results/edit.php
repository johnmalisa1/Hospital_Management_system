<?php
session_start();
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Admin' && $_SESSION['role'] !== 'Doctor')) {
    header("Location: ../../login.php");
    exit();
}

include "../../config/db.php";
require_once "../../includes/classes/LabTestResult.php";

$id = $_GET['id'];
$labTestResult = new LabTestResult($db);
$row = $labTestResult->getResultById($id);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result_text = $_POST['result_text'];
    $result_date = $_POST['result_date'];

    $labTestResult->updateResult($id, $result_text, $result_date);
    header("Location: view.php");
}
?>

<a href="view.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back to lab_test_results</a>
<h2 style="text-align:center;">Edit Lab Test Result</h2>
<form method="POST" style="width:500px;margin:auto;padding:30px;background:white;border-radius:10px;box-shadow:0 0 10px #ccc;">
    <label>Result Text:</label>
    <textarea name="result_text" required style="width:100%;height:100px;"><?= $row['result_text'] ?></textarea><br><br>

    <label>Result Date:</label>
    <input type="date" name="result_date" value="<?= $row['result_date'] ?>" required style="width:100%;padding:10px;"><br><br>

    <button type="submit" style="background:#28a745;color:white;padding:10px 20px;">Update</button>
</form>

