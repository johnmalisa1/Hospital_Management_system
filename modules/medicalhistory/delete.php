<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Admin') {
    header("Location: ../../login.php");
    exit();
}
include "../../config/db.php";
require_once "../../includes/classes/MedicalHistory.php";

$id = $_GET['id'];
$medicalHistory = new MedicalHistory($db);
$medicalHistory->deleteHistory($id);

header("Location: view.php");
exit();
?>
