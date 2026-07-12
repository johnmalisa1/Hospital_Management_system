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
$labTestResult->deleteResult($id);
header("Location: view.php");
exit();
